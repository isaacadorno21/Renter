<?php 
  session_start();
?>

<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Renter</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>Home Page</h1>
<p>Items That Are Available For Me To Rent</p>
</header>

<!-- Nav -->

<nav id="nav">
<ul>
	<li><a class=#active href="home_page.php">Home Page</a></li>
	<li><a href="my_items.php">My Items</a></li>
	<li><a href="loaned_items.php">Loaned Items</a></li>
	<li><a href="account.php">Account</a></li>
	<li><a href="recommended.php">Recommended Items</a></li>
</ul>
</nav>
<!-- Main -->

<div id="main"><!-- Introduction -->

  <!--Display user info-->
    <div style="text-align: left; margin-left: 85%; transform:scale(0.7);"> 
        <?php
            if (isset($_SESSION['username'])) {

                echo "Signed in As: ".$_SESSION['username'];
            }
        ?>

      <!--Sign out-->

            <form method="post"> 
                 <input type="submit" name="sign_out" value="Sign Out">
            </form>
    </div>
    
<?php
    if (isset($_POST['sign_out'])) {
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
        echo "<script> location.href='index.html'; </script>";
    }
?>

<div style="margin-left: 5%;  margin-right: 5%;">

<h3><b>Recommended Items for You</b></h3>
<p> Based on distance from you, as well as previous interactions with the Owner and how many times you have rented objects of that Category.</p>

<?php
    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";
    
    $cur_user_id = $_SESSION['user_id'];
    $search_query = $_GET["search_query"];
    
    // Create connection
    $mysqli = mysqli_connect($servername, $username, $password, $database);
    if (mysqli_connect_errno($mysqli)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    $result = mysqli_query($mysqli, "DROP PROCEDURE GetRecommended;");
    
    $sqlprocedure = "
CREATE PROCEDURE GetRecommended(IN given_user_id INT, IN num_results INT)
BEGIN
	DECLARE given_latitude REAL DEFAULT (
	    SELECT latitude FROM User WHERE user_id = given_user_id
	);
	DECLARE given_longitude REAL DEFAULT (
	    SELECT longitude FROM User WHERE user_id = given_user_id
	);

	DECLARE curr_item_id INT;
	DECLARE curr_category_id INT;
	DECLARE curr_owner_id INT;
	DECLARE curr_latitude REAL;
	DECLARE curr_longitude REAL;
	DECLARE curr_category_count INT;
	DECLARE curr_owner_count INT;
	DECLARE curr_distance REAL;
	DECLARE curr_score REAL;

	DECLARE done INT DEFAULT 0;
	DECLARE cur CURSOR FOR (
		SELECT item_id, category_id, owner_id, latitude, longitude
		FROM Items NATURAL JOIN Owner NATURAL JOIN User
		WHERE user_id != given_user_id
	);
	DECLARE CONTINUE HANDLER FOR NOT FOUND
		SET done = 1;
    OPEN cur;

	DROP TABLE IF EXISTS Recommended;
	CREATE TABLE Recommended (
		item_id INT,
		item_score REAL
	);

	REPEAT
	    FETCH cur INTO curr_item_id, curr_category_id, curr_owner_id, curr_latitude, curr_longitude;		
    	
    	IF NOT done THEN
    		SET curr_category_count = (
    			SELECT COUNT(*)
    			FROM Items NATURAL JOIN Loans NATURAL JOIN Rentee 
    			WHERE user_id = given_user_id
    			AND category_id = curr_category_id
    		);
    		SET curr_owner_count = (
    			SELECT COUNT(*)
    			FROM Loans NATURAL JOIN Rentee
    			WHERE user_id = given_user_id
    			AND owner_id = curr_owner_id
    		);
    		/* lat/lon formula from https://stackoverflow.com/a/33350400 */
    		SET curr_distance = ACOS(SIN(PI()*given_latitude/180.0)*SIN(PI()*curr_latitude/180.0)+COS(PI()*given_latitude/180.0)*COS(PI()*curr_latitude/180.0)*COS(PI()*curr_longitude/180.0-PI()*given_longitude/180.0))*6371;
    		
    		SET curr_score = 100 * (2 + curr_category_count) * (1 + curr_owner_count) / (10 + curr_distance);
    		
    		INSERT INTO Recommended VALUES (curr_item_id, curr_score);
    		IF (SELECT COUNT(*) FROM Recommended) > num_results THEN
    			DELETE FROM Recommended ORDER BY item_score LIMIT 1;
    		END IF;
    	END IF;
	UNTIL done
	END REPEAT;
	CLOSE cur;
END;
    ";

    $result = mysqli_query($mysqli, $sqlprocedure);
    echo mysqli_error($mysqli);
    
    $result = mysqli_query($mysqli, "CALL GetRecommended($cur_user_id, 3)");
    echo mysqli_error($mysqli);

    /*
    $result = mysqli_query($mysqli, "SELECT * FROM Recommended");
    echo mysqli_error($mysqli);

    echo "<table><tr>
        <th>Item ID</th>
        <th>Score</th>
        </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $item_id = $row["item_id"];
        $score = $row["item_score"];
        echo "<tr><td>".$item_id."</td><td>".$score."</td></tr>";
    }
    echo "</table>";
    echo "<br>delet this<br>";
    */

    $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM User where user_id = '$cur_user_id';"));
    $lat1 = $row["latitude"];
    $lon1 = $row["longitude"];
    
    if (empty($search_query)) {
        $search_query = "%";
    }
    $sqlquery = "SELECT item_score, category_name, item_id, photo, item_name, cost, latitude, longitude FROM Recommended NATURAL JOIN Items NATURAL JOIN Category NATURAL JOIN Owner NATURAL JOIN User ORDER BY item_score DESC";
    echo mysqli_error($mysqli);
    
    
    if ($result = mysqli_query($mysqli, $sqlquery)) {
            echo "<table>";
            echo "<tr>
            <th>Rec. Score</th>
            <th>Photo</th>
            <th>Item Name</th>
            <th>Cost</th>
            
            <th>Category</th>
            <th>Distance</th>
            </tr>"; 
            /* fetch associative array */
            while ($row = mysqli_fetch_assoc($result)) {
                $item_score = ROUND($row["item_score"], 3);
                $item_id = $row["item_id"];
                $photo = $row["photo"];
                $photo_full = "<img src='images/item_photos/" . $photo . "' alt='error' height='50' width='50'>";
                $item_name = $row["item_name"];
                $cost = $row["cost"];
                
                $category = $row["category_name"];
                
                $lat2 = $row["latitude"];
                $lon2 = $row["longitude"];
                $distance = ROUND(ACOS(SIN(PI()*$lat1/180.0)*SIN(PI()*$lat2/180.0)+COS(PI()*$lat1/180.0)*COS(PI()*$lat2/180.0)*COS(PI()*$lon2/180.0-PI()*$lon1/180.0))*6371, 1);

            echo "<tr><td>".$item_score."</td><td><a href=\"redirect.php?item=$item_id\">".$photo_full."</a></td><td>".$item_name."</td><td>".'$'.$cost."</td><td>".$category."</td><td>".$distance." miles"."</td></tr>";
            }
            echo "</table>";
    }
?>
</div>

<br>
</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">Created by UIUC CS411 students - Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
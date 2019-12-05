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
<h1>Loaned Items</h1>
<p>Items Rented Out by Me</p>
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

<br>

 <div style="margin-left:5%; margin-right:5%; border:solid 1px grey;">
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
        
        //get rentee id
        $sqlrentee = "SELECT rentee_id FROM Rentee WHERE Rentee.user_id = $cur_user_id";
        if ($result = mysqli_query($mysqli, $sqlrentee)) {
            if ($row = mysqli_fetch_assoc($result)){
                $renteeid = $row["rentee_id"];
            }
        }
        else{
            echo "rentee lost :("."$cur_user_id";
        }
    
        $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM User where user_id=$username;"));
        $lat1 = $row["latitude"];
        $lon1 = $row["longitude"];
        //$cur_user_id = Loans.rentee_id
        //AND $renteeid = Loans.rentee_id
        
            $sqlquery = "SELECT category_name, item_id, photo, item_name, cost, description 
                        FROM  Category NATURAL JOIN Items NATURAL JOIN Loans
                        WHERE Loans.item_id = Items.item_id AND Loans.rentee_id = $renteeid";
                     
        
        
        if ($result = mysqli_query($mysqli, $sqlquery)) {
                echo "<table>";
                echo "<tr>
                <th>Photo</th>
                <th>Item Name</th>
                <th>Cost</th>
                <th>Description</th>
                <th>Category</th>
                <th>Action</th>
                </tr>"; 
                /* fetch associative array */
                while ($row = mysqli_fetch_assoc($result)) {
                    $item_id = $row["item_id"];
                    $photo = $row["photo"];
                    $photo_full = "<img src='images/item_photos/" . $photo . "' alt='error' height='50' width='50'>";
                    $item_name = $row["item_name"];
                    $cost = $row["cost"];
                    $description = $row["description"];
                    $category = $row["category_name"];
    
                echo "<tr>
                <td>".$photo_full."</td>
                <td>".$item_name."</td>
                <td>".'$'.$cost."</td>
                <td>".$description."</td>
                <td>".$category."</td>
                <td><button class='btn'><a href=\"redirectreturn.php?item=$item_id\">Return Item</a></button></td></tr>";
                }
                echo "</table>";
                echo "&nbsp";
                echo 'Total results: ' . $result->num_rows;
        }
    ?>

</div>
<br>
<div style='margin-left:5%;'><h3><b>History</b></h3></div>
<div style='margin-left:5%;'><p>Shows a running history of people you have rented from or have rented to.</p></div>

 <div style="margin-left:5%; margin-right:5%; border:solid 1px grey;">
    <?php
        require_once "./SleekDB-master/src/SleekDB.php";
        $dataDir = "./SleekDB-master/data";
        $relationStore = \SleekDB\SleekDB::store('Relationships', $dataDir);

        // fetch sleekdb data for friends
        $cur_user_id = $_SESSION['user_id'];
        $friends = $relationStore->where('rentee_id', '=', $cur_user_id)->orwhere('owner_id', '=', $cur_user_id)->fetch();
        //echo print_r(array_values($friends));
        $i = 0;
        foreach($friends as $result) {
            $friend_ids[$i] = $result['rentee_id'];
            $i = $i + 1;
        }
        foreach($friends as $result) {
            $friend_ids[$i] = $result['owner_id'];
            $i = $i + 1;
        }
        
        //print_r($friend_ids);
        
        $servername = "localhost";
        $username = "uiucrenter_mainuser";
        $password = "uiucrenter_mainuser";
        $database = "uiucrenter_main";
        
        $search_query = $_GET["search_query"];
        
        $ids = join("','",$friend_ids);
        // Create connection
        $mysqli = mysqli_connect($servername, $username, $password, $database);
        $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM User where user_id IN ('$ids') AND where user_id NOT IN ('$cur_user_id');"));
        
        $sqlquery = "SELECT * FROM User where user_id IN ('$ids') AND user_id <> $cur_user_id;";
        //username, full_name, city, phone_number, latitude, langitude
        //  AND where user_id <> $cur_user_id
        
        
        if ($result = mysqli_query($mysqli, $sqlquery)) {
                echo "<table>";
                echo "<tr>
                <th>Username</th>
                <th>Name</th>
                <th>City</th>
                <th>Phone Number</th>
                <th>Latitude</th>
                <th>Longitude</th>
                </tr>"; 
                // fetch associative array 
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $item_id = $row["username"];
                    $photo = $row["full_name"];
                    $item_name = $row["city"];
                    $cost = $row["phone_number"];
                    $description = $row["latitude"];
                    $category = $row["longitude"];
    
                echo "<tr>
                <td>".$item_id."</td>
                <td>".$photo."</td>
                <td>".$item_name."</td>
                <td>".$cost."</td>
                <td>".$description."</td>
                <td>".$category."</td>
                </tr>";
                }
                echo "</table>";
                echo "&nbsp";
                echo 'Total results: ' . $result->num_rows;
        } else {
            echo "No history, rent some items and relations will appear here";
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
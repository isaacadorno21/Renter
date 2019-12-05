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
<h1>My Items</h1>
<p>Items I Have Put Up For Others To Rent</p>
</header>

<!-- Nav -->

<nav id="nav">
<ul>
	<li><a href="home_page.php">Home Page</a></li>
	<li><a class=#active href="my_items.php">My Items</a></li>
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
    <!--SHOW OWNER BIO-->
    <div style="text-align: center;">
        <h3><b>My Owner Bio</b></h3>
        <?php
            $servername = "localhost";
            $username = "uiucrenter_mainuser";
            $password = "uiucrenter_mainuser";
            $database = "uiucrenter_main";
            
            $cur_user_id = $_SESSION['user_id'];
            
            // Create connection
            $mysqli = mysqli_connect($servername, $username, $password, $database);
            
            if (mysqli_connect_errno($mysqli)) {
               echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            
           /* $sqlquery = "SELECT COUNT(*) AS num_rented, SUM(cost) as revenue, AVG(owner_rating) as avg_rating
                FROM Owner NATURAL JOIN Loans NATURAL JOIN Items
                WHERE user_id = '$cur_user_id'
                GROUP BY owner_id";
           */
           
             //get owner id of user
            $sqlselect = "SELECT owner_id FROM Owner WHERE $cur_user_id = Owner.user_id";
                    if ($result = mysqli_query($mysqli, $sqlselect)){
                        while ($row = mysqli_fetch_assoc($result)){
                            $ownerid = $row['owner_id'];
                        }
                    }
                    else{
                        echo "failed to grab owner";
                    }
           //show bio
           $sqlquery = "SELECT owner_bio, instructions FROM Owner WHERE $ownerid = Owner.owner_id";
            if ($result = mysqli_query($mysqli, $sqlquery)) {
                $row = mysqli_fetch_assoc($result);
                $bio = $row['owner_bio'];
                $instructions = $row['instructions'];
                echo "Owner Bio: $bio"."<br>";
                echo "Owner Instructions: $instructions";
            } else {
                echo "No owner info to show.";
            }
        ?>
        <br></br>
         <input type="button" value="Edit Bio" onclick="window.location='/updateowner.php';">
</div>

<br>

 <div style="margin-left:5%; margin-right:5%; border:solid 1px grey;">
        <?php
            $servername = "localhost";
            $username = "uiucrenter_mainuser";
            $password = "uiucrenter_mainuser";
            $database = "uiucrenter_main";
            
            $cur_user_id = $_SESSION['user_id'];
            
            // Create connection
            $mysqli = mysqli_connect($servername, $username, $password, $database);
            
            if (mysqli_connect_errno($mysqli)) {
               echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            
            
            //get owner id of user
            $sqlselect = "SELECT owner_id FROM Owner WHERE $cur_user_id = Owner.user_id";
                    if ($result = mysqli_query($mysqli, $sqlselect)){
                        while ($row = mysqli_fetch_assoc($result)){
                            $ownerid = $row['owner_id'];
                        }
                    }
                    else{
                        echo "failed to grab owner";
                    } 
                
            $sqlquery = "SELECT category_name, photo, item_name, item_id, cost, description FROM Category JOIN Items ON Category.category_id = Items.category_id WHERE Items.owner_id = '$ownerid'";
            
            if ($result = mysqli_query($mysqli, $sqlquery)) {
                    echo "<table>";
                    echo "<tr>
                    <th>Photo</th>
                    <th>Item Name</th>
                    <th>Cost</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Edit</th>
                    <th>Remove</th>
                    </tr>"; 
                    $question = 'Are you sure you want to delete this item?';
                    /* fetch associative array */
                    while ($row = mysqli_fetch_assoc($result)) {
                        $photo = $row["photo"];
                        $photo_full = "<img src='images/item_photos/" . $photo . "' alt='error' height='50' width='50'>";
                        $item_name = $row["item_name"];
                        $item_id = $row["item_id"];
                        $cost = $row["cost"];
                        $description = $row["description"];
                        $category = $row["category_name"];
        
                    echo "<tr>
                        <td>".$photo_full."</td>
                        <td>".$item_name."</td>
                        <td>".'$'.$cost."</td>
                        <td>".$description."</td>
                        <td>".$category."</td>
                        <td> <button class='btn'><a href=\"updateredirect.php?item=$item_id\">Update</a></button></td>
                        <td>
                        <button class='btn'><a href=\"deleteredirect.php?item=$item_id\" onclick='return confirm('Are you sure you want to remove this item?')'>Delete</a></button>

                        </tr>";
                    }
                    echo "</table>";
                    echo "&nbsp";
                    echo 'Total results: ' . $result->num_rows;
            }
        ?>
        <br>
    </div>
    
    <br><br>
    <!--ADD NEW ITEM-->
    <div style="margin-left:5%; ">
        <form method="post">
            <input type="submit" name="add_item" value="Add New Item">
        </form>
        
        <?php
            if (isset($_POST['add_item'])) {
                
                $servername = "localhost";
                $username = "uiucrenter_mainuser";
                $password = "uiucrenter_mainuser";
                $database = "uiucrenter_main";
            
                $cur_user_id = $_SESSION['user_id'];
                
                // Create connection
                $mysqli = mysqli_connect($servername, $username, $password, $database);
                
                if (mysqli_connect_errno($mysqli)) {
                   echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
                
                $sqlquery = "SELECT owner_id FROM Owner WHERE Owner.user_id = '$cur_user_id'";
                $result = mysqli_query($mysqli, $sqlquery); 
                    
                if (mysqli_num_rows($result) == 0) { 
                    //if user does not exist in owner table
                    echo "<script> location.href='add_owner.php'; </script>";
                }
                else {
                    echo "<script> location.href='add_items.php'; </script>";
                }
            }
        ?>
        <br>
    </div>
</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">Created by UIUC CS411 students - Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
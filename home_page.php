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

<div style="margin-left:5%; margin-right:5%; ">
    <div style="width:45%; margin:auto; float: left;">
      <div style="display:inline-block; width:80%;">
            <form>
            Search for Available Items by Name:<br>
            <input type="text" name="search_query"><br>
            <input type="submit" name="search" value="Search">
            </form> 
        </div>
    </div>
    
    <div style="width:10%; margin:auto; float: left;">
        <div style="display:inline-block; width:80%;">
            <br>
            <h3>Or</h3>
        </div>
    </div>
            
    <div style="width:45%; margin:auto;float:left;">
        <div style="display:inline-block; width:80%;">
            
              Browse by Filter:
              <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">

              <select name="filter">
                <option value="" selected disabled hidden>Select Filter</option>
                <option value="avail" >Available Items</option>
                <option value="all">All Items</option>
              </select>
              <br>
              <input type="submit" name="submit">
            </form>
        </div>
    </div>
</div>
<br><br><br><br><br><br></br>

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
            
            
            //Grab display choice
             if(isset($_POST['submit'])) {
                $filter = $_POST['filter'];
                if($filter =='avail'){//-----------------------------------------------
                    //print available items
                    //including search query from avail items
                     echo "<div style='margin-left:5%;'><h3><b>"."Available Items"."</b></h3></div>";   
                    echo "    <div style='margin-left:5%; margin-right:5%; border:solid 1px grey; '>";
                    $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM User where user_id = '$cur_user_id';"));
                    $lat1 = $row["latitude"];
                    $lon1 = $row["longitude"];
                    
                    if (empty($search_query)) {
                        $search_query = "%";
                    }
                    $sqlquery = "SELECT category_name, item_id, photo, item_name, cost, description, latitude, longitude FROM Category NATURAL JOIN Items NATURAL JOIN Owner NATURAL JOIN User 
                        WHERE user_id != '$cur_user_id' AND item_name LIKE '$search_query'
                        AND item_id NOT IN (SELECT item_id FROM Loans)";
                    //take out loaned items
                    
                    if ($result = mysqli_query($mysqli, $sqlquery)) {
                        echo "<table>";
                        echo "<tr>
                        <th>Photo</th>
                        <th>Item Name</th>
                        <th>Cost</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Distance</th>
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
                            
                            $lat2 = $row["latitude"];
                            $lon2 = $row["longitude"];
                            $distance = ROUND(ACOS(SIN(PI()*$lat1/180.0)*SIN(PI()*$lat2/180.0)+COS(PI()*$lat1/180.0)*COS(PI()*$lat2/180.0)*COS(PI()*$lon2/180.0-PI()*$lon1/180.0))*6371, 1);
            
                        echo "<tr><td><a href=\"redirect.php?item=$item_id\">".$photo_full."</a></td><td>".$item_name."</td><td>".'$'.$cost."</td><td>".$description."</td><td>".$category."</td><td>".$distance." miles"."</td></tr>";
                        }
                        echo "</table>";
                        echo "&nbsp";
                        echo 'Total results: ' . $result->num_rows;
                        echo '</div>';
                    }
                } //non available too
                else {//-------------------------------------------------------------
                    //print all items
                    //search query from all items
                     echo "<div style='margin-left:5%;'><h3><b>"."All Items"."</b></h3></div>"; 
                    echo "    <div style='margin-left:5%; margin-right:5%; border:solid 1px grey; '>";
                      $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM User where user_id = '$cur_user_id';"));
                    $lat1 = $row["latitude"];
                    $lon1 = $row["longitude"];
                    
                    if (empty($search_query)) {
                        $search_query = "%";
                    }
                //take out latitiude and longitude and USer table
                  $sqlquery = "SELECT category_name, item_id, photo, item_name, cost, description, latitude, longitude FROM Category NATURAL JOIN Items NATURAL JOIN Owner NATURAL JOIN User 
                        WHERE user_id != '$cur_user_id' AND item_name LIKE '$search_query'";
                //take out loaned items
                
                    if ($result = mysqli_query($mysqli, $sqlquery)) {
                            echo "<table>";
                            echo "<tr>
                            <th>Photo</th>
                            <th>Item Name</th>
                            <th>Cost</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Distance</th>
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
                                
                                $lat2 = $row["latitude"];
                                $lon2 = $row["longitude"];
                                $distance = ROUND(ACOS(SIN(PI()*$lat1/180.0)*SIN(PI()*$lat2/180.0)+COS(PI()*$lat1/180.0)*COS(PI()*$lat2/180.0)*COS(PI()*$lon2/180.0-PI()*$lon1/180.0))*6371, 1);
                
                            echo "<tr><td>".$photo_full."</td><td>".$item_name."</td><td>".'$'.$cost."</td><td>".$description."</td><td>".$category."</td><td>".$distance." miles"."</td></tr>";
                            }
                            echo "</table>";
                            echo "&nbsp";
                            echo 'Total results: ' . $result->num_rows;
                            echo '</div>';
                    }
                }
            } 
            else{//----------------------------------------------isnotsetPOST-----------
                echo "<div style='margin-left:5%;'><h3><b>"."Available Items"."</b></h3></div>";        
                echo "<div style='margin-left:5%; margin-right:5%; border:solid 1px grey; '>";
                $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM User where user_id = '$cur_user_id';"));
                $lat1 = $row["latitude"];
                $lon1 = $row["longitude"];
                
                if (empty($search_query)) {
                    $search_query = "%";
                }
                $sqlquery = "SELECT category_name, item_id, photo, item_name, cost, description, latitude, longitude, full_name, owner_bio, instructions FROM Category NATURAL JOIN Items NATURAL JOIN Owner NATURAL JOIN User 
                    WHERE user_id != '$cur_user_id' AND item_name LIKE '$search_query'
                    AND item_id NOT IN (SELECT item_id FROM Loans)";
                //take out loaned items
                
                if ($result = mysqli_query($mysqli, $sqlquery)) {
                        echo "<table>";
                        echo "<tr>
                        <th>Photo</th>
                        <th>Item Name</th>
                        <th>Cost</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Distance</th>
                        <th>Owner Name</th>
                        <th>Owner Bio</th>
                        <th>Owner Instructions</th>
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
                            
                            $lat2 = $row["latitude"];
                            $lon2 = $row["longitude"];
                            $distance = ROUND(ACOS(SIN(PI()*$lat1/180.0)*SIN(PI()*$lat2/180.0)+COS(PI()*$lat1/180.0)*COS(PI()*$lat2/180.0)*COS(PI()*$lon2/180.0-PI()*$lon1/180.0))*6371, 1);
                            $ownername = $row["full_name"];
                            $ownerbio = $row["owner_bio"];
                            $instructions = $row["instructions"];
                            
            
                        echo "<tr><td><a href=\"redirect.php?item=$item_id\">".$photo_full."</a></td><td>".$item_name."</td><td>".'$'.$cost."</td><td>".$description."</td><td>".$category."</td><td>".$distance." miles"."</td><td>".$ownername."</td><td>".$ownerbio."</td><td>".$instructions."</td></tr>";
                        }
                        echo "</table>";
                         echo "&nbsp";
                        echo 'Total results: ' . $result->num_rows;
                        echo '</div>';
                    }
            }

        ?>
       <br>
        
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
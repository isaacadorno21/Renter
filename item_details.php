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
<h1>Rent Item</h1>
</header>
<nav id="nav">
<ul>
	<li><a href="home_page.php">Home Page</a></li>
	<li><a class=#active href="my_items.php">My Items</a></li>
	<li><a href="loaned_items.php">Loaned Items</a></li>
	<li><a href="account.php">Account</a></li>
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
       <div style="margin-left: 5%;"> <h3>Item Details</h3></div>
 <div style="margin-left:5%; margin-right:5%; border:solid 1px grey;">
        <?php
            $servername = "localhost";
            $username = "uiucrenter_mainuser";
            $password = "uiucrenter_mainuser";
            $database = "uiucrenter_main";
            
            $cur_user_id = $_SESSION['user_id'];
            $cur_item = $_SESSION['item_id'];

            
            // Create connection
            $mysqli = mysqli_connect($servername, $username, $password, $database);
            
            if (mysqli_connect_errno($mysqli)) {
               echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            
            
            //get owner id of user
            $sqlselect = "SELECT owner_id, instructions, owner_bio, full_name, phone_number FROM Items NATURAL JOIN Owner NATURAL JOIN User WHERE $cur_item = Items.item_id";
                    if ($result = mysqli_query($mysqli, $sqlselect)){
                        while ($row = mysqli_fetch_assoc($result)){
                            $ownerid = $row['owner_id'];
                            $instructions = $row['instructions'];
                            $owner_name = $row['full_name'];
                            $phone = $row['phone_number'];
                        }
                    }
                    else{
                        echo "failed to grab owner";
                    } 
                
            $sqlquery = "SELECT category_name, photo, item_name, item_id, cost, description FROM Category JOIN Items ON Category.category_id = Items.category_id WHERE Items.item_id = '$cur_item'";
            
            if ($result = mysqli_query($mysqli, $sqlquery)) {
                    echo "<table>";
                    echo "<tr>
                    <th>Photo</th>
                    <th>Item Name</th>
                    <th>Cost</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Owner </th>
                    <th>Owner Information</th>
                    </tr>"; 
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
                        <td>".$owner_name."</td>
                        <td> Phone: ".$phone."<br>$instructions"."</td></tr>";
                    }
                    echo "</table>";
            }
        ?>
    </div>
    <br>
    <div style="margin-left: 5%;">
        <form method="post">
            <input type="submit" name="submit" value="Rent Item"><br></br>
            <input type="button" value="Cancel" onclick="window.location='/home_page.php';">

        </form> 
    </div>

<?php
  
    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";
    
    $cur_user_id = $_SESSION['user_id'];
    $cur_item = $_SESSION['item_id'];
    
    // Create connection
    $mysqli = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_errno($mysqli)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    if(isset($_POST['submit'])) {
       
        //UPDATE RENTEE TABLE--------------------------------------------------
        
        $sqlinsert = "INSERT INTO Rentee (user_id) VALUES ('$cur_user_id')";
        
        if (mysqli_query($mysqli, $sqlinsert)) {
            //new rentee
        }
        else {
            //existing rentee
        }
        
        //UPDATE OWNER TABLE---------------------------------------------------
        //GET OWNER------------------------------------------------------------
        $sqlselect = "SELECT owner_id FROM Items WHERE $cur_item = Items.item_id";
         if ($result = mysqli_query($mysqli, $sqlselect)) {
             while($row = mysqli_fetch_assoc($result)){
                $ownerid = $row["owner_id"];
             }
        }
        else {
            echo "owner not grabbed";
        }
        
        //GET DATE-------------------------------------------------------------
            $date = date('Y-m-d');
       //GET RENTEE ID---------------------------------------------------------
       $sqlrentee = "SELECT rentee_id from Rentee WHERE $cur_user_id = Rentee.user_id";
        if ($result = mysqli_query($mysqli, $sqlrentee)) {
        while ($row = mysqli_fetch_assoc($result)){
            $renteeid = $row["rentee_id"];

        }
    }
    else{
        echo "error";
    }
       //UPDATE LOANS TABLE----------------------------------------------------
       $sqlinsertl= "INSERT INTO Loans (owner_id, rentee_id, item_id, rented_date) VALUES
           ('$ownerid', '$renteeid', '$cur_item', '$date')";
           
        require_once "./SleekDB-master/src/SleekDB.php";
        
        if (mysqli_query($mysqli, $sqlinsertl)) {
            // insert item info into sleekdb
            $dataDir = "./SleekDB-master/data";
            $relationStore = \SleekDB\SleekDB::store('Relationships', $dataDir);
            
            // get user_id from owner_id
            $usr_id_owner = -1;
            $sqlselect = "SELECT user_id FROM Owner WHERE owner_id = $ownerid";
            if ($result = mysqli_query($mysqli, $sqlselect)) {
                 while($row = mysqli_fetch_assoc($result)){
                    $usr_id_owner = $row["user_id"];
                 }
            }
            else {
                echo "user not in owners table";
            }
            
            echo $usr_id_owner, $cur_user_id;
            $relationInsertable = [
                "owner_id" => $usr_id_owner,
                "rentee_id" => $cur_user_id,
                "item_id" => $cur_item,
                "rented_date" => $date,
                "returned_date" => NULL
            ];
            $results = $relationStore->insert( $relationInsertable );

                     echo "<script> location.href='loaned_items.php'; </script>";
            
        }
        else {
            echo "loans failed";

        }
        
            
    }
    
?>
<br><br>

</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">Created by UIUC CS411 students - Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
<?php 
  session_start();
?>

<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Add New Item - Stellar by HTML5 UP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>Add New Item</h1>
<h1>Add An Item For Others To Rent!</h1>
</header>

<!-- Nav -->

<nav id="nav">
<ul>
	<li><a href="home_page.php">Home Page</a></li>
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
<!--Display For-->
<div style="margin-left: 5%; margin-right: 5%;">
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        
        <div style="width: 80%;">
        Upload a Photo:<br>
        <input type="file" name="pic" id="pic">
        <br>
        
        Item Name:<br>
        <input type="text" name="item_name">
        <br>
        
        Cost per day: (Do not include dollar sign)<br>
        <input type="text" name="cost">
        <br>
        
        Description:<br>
        <input type="text" name="description">
        <br>
        
        Category:<br>
        <select name = "cur_category">
        <?php 
            $servername = "localhost";
            $username = "uiucrenter_mainuser";
            $password = "uiucrenter_mainuser";
            $database = "uiucrenter_main";
            
            // Create connection
            $mysqli = mysqli_connect($servername, $username, $password, $database);
            $sql = mysqli_query($mysqli, "SELECT category_id, category_name FROM Category");
            while ($row = $sql->fetch_assoc()) {
                echo '<option value="'.$row['category_id'].'">'.$row['category_name'].'</option>';
            }
        ?>
        </div>
    </select>
    <br>
    
    <input type="submit" value="Add Item" name="submit"> <br></br>
    <input type="button" value="Cancel" onclick="window.location='/my_items.php';">
    </form> 
</div>
<?php

    //All the image upload stuff
    
    $targetDir = "images/item_photos/";
    $fileName = $_FILES['pic']['name'];
    $fileTmpName  = $_FILES['pic']['tmp_name'];
    $fileType = $_FILES['pic']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $uploadPath = $targetDir . basename($fileName); 
    
    $fileExtensions = ['jpeg','jpg','png', 'gif'];

    if(isset($_POST['submit'])) {
        
        if (! in_array($fileExtension,$fileExtensions)) {
            $errors[] = "This file extension is not allowed. Please upload an image.";
        }
        
        else {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            if ($didUpload) {
                echo "The file " . basename($fileName) . " has been uploaded"."<br/>";
            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }
        }
        
        $servername = "localhost";
        $username = "uiucrenter_mainuser";
        $password = "uiucrenter_mainuser";
        $database = "uiucrenter_main";
    
        $item_name = $_POST["item_name"];
        $cost = $_POST["cost"];
        $description = $_POST["description"];
        $user_id = $_SESSION['user_id'];
        $category_id = $_POST['cur_category'];
        
        $cur_user = $_SESSION['user_id'];
        
        // Create connection
        $mysqli = mysqli_connect($servername, $username, $password, $database);
        
        if (mysqli_connect_errno($mysqli)) {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        if (!empty($item_name) && !empty($cost)) {
           
            //UPDATE ITEMS----------------------------------------------
            
            //returning owner
            //get owner id to insert into items table
            $sqlselect = "SELECT owner_id FROM Owner WHERE $cur_user = Owner.user_id";
            if ($result = mysqli_query($mysqli, $sqlselect)){
                while ($row = mysqli_fetch_assoc($result)){
                    $ownerid = $row['owner_id'];
                    echo"$ownerid";
                }
            }
            else{
                echo "failed to grab owner";
            }
        
            
            //ADD TO ITEMS TABLE-----------------------------------------------;
            $sqlinsert = "INSERT INTO Items (photo, item_name, cost, description, owner_id, category_id) VALUES ('$fileName', '$item_name', '$cost', '$description', '$ownerid', '$category_id')";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "New record created successfully"."<br/>";
                       echo "<script> location.href='my_items.php'; </script>";
            }
            else {
                echo "oops";
                echo"'$item_name', '$cost', '$description', '$ownerid', '$category_id'";
            }
            
        }
    }
?>

</section>
</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">CS411 Fall 2019 Renter</p>.</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
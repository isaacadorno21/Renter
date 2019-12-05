<?php 
  session_start();
?>

<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Update - Stellar by HTML5 UP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>Update</h1>
<p>Update Owner Bio</p>
</header>
<nav id="nav">
<ul>
	<li><a href="home_page.php">Home Page</a></li>
	<li><a class=#active href="my_items.php">My Items</a></li>
	<li><a href="loaned_items.php">Loaned Items</a></li>
	<li><a href="account.php">Account</a></li>
	<li><a href="recommended.php">Recommended Items</a></li>
</ul>
</nav>


<div id="main"><!-- Content -->
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
    
<!--Display Form-->
<div style="margin-left: 15%; ">
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
            <div style="width: 80%;">
            Update Owner Bio:<br>
            <input type="text" name="owner_bio" >
            <br>
            Update Pick Up Instructions:<br>
            <input type="text" name="instructions" >
            <br>

            <input type="submit" value="Update" name="submit"> <br></br>
            <input type="button" value="Cancel" onclick="window.location='/my_items.php';">
        </form> 
        <br>
    </div>

    
<?php
    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";

    $cur_user_id = $_SESSION['user_id'];
    $cur_item = $_SESSION['item_id'];


     $bio = $_POST["owner_bio"];
     $instructions = $_POST["instructions"];


    // Create connection
    $mysqli = mysqli_connect($servername, $username, $password, $database);
    
    if (mysqli_connect_errno($mysqli)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    //GET OWNER
    $sqlowner = "SELECT owner_id FROM Owner WHERE $cur_user_id = Owner.user_id";
    
    if($result = mysqli_query($mysqli, $sqlowner)){
        //owner_id retrieved
          while ($row = mysqli_fetch_assoc($result)){
                $ownerid = $row['owner_id'];
          }
    }
    else{
        echo "Failed to Retrieve Owner Id";
    }
    
    if(isset($_POST['submit'])) {
       
      
        //UPDATE BIO-----------------------------------------------------
        if (!empty($bio)) {
            $sqlinsert = "UPDATE Owner SET owner_bio ='$bio' WHERE $ownerid = Owner.owner_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                echo "<script> location.href='my_items.php'; </script>";
            }
            else {
                echo "Owner bio failed to update";
            }
        }
        
        //UPDATE INSTRUCTIONS-----------------------------------------------------
        if (!empty($instructions)) {
            $sqlinsert = "UPDATE Owner SET instructions ='$instructions' WHERE $ownerid = Owner.owner_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                echo "<script> location.href='my_items.php'; </script>";
            }
            else {
                echo "Owner Instructions failed to update";
            }
        }
        
    }

?>
</div>
    
<br>
</section>
</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">CS411 Fall 2019 Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
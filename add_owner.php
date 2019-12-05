<?php 
  session_start();
?>

<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Add New Owner Info - Stellar by HTML5 UP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>Add New Owner Info</h1>
</header>

<!-- Nav -->

<nav id="nav">
<ul>
	<li><a href="home_page.php">Home Page</a></li>
	<li><a href="my_items.php">My Items</a></li>
	<li><a href="loaned_items.php">Loaned Items</a></li>
	<li><a href="account.php">Account</a></li>
</ul>
</nav>

<!-- Main -->

<div id="main"><!-- Introduction -->

<?php
    if (isset($_SESSION['username'])) {
        echo "<strong>";
        echo "Current User: ".$_SESSION['username'];
        echo "<br>User ID: ".$_SESSION['user_id'];
        echo "</strong>";
    }
?>

<form method="post">
<input type="submit" name="sign_out" value="Sign Out">
</form>

<?php
    if (isset($_POST['sign_out'])) {
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
        echo "<script> location.href='index.html'; </script>";
    }
?>

<p>It appears that this is your first time putting an item up for rent! Feel free to add a small bio and a list of instructions for the people who rent your items.</p>

<form method = "post">
Owner Bio:<br>
<input type="text" name="bio">
<br>
Owner Instructions:<br>
<input type="text" name="instruct">
<br>
<input type="submit" name = "submit" value="Add Information">
</form> 

<?php

    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";
    
    if(isset($_POST['submit'])) {
        $user_id = $_SESSION['user_id'];
        $bio = $_POST["bio"];
        $instruct = $_POST["instruct"];
        
        // Create connection
        $mysqli = mysqli_connect($servername, $username, $password, $database);
        
        if (mysqli_connect_errno($mysqli)) {
           echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $sqlinsert = "INSERT INTO Owner (user_id,owner_bio, instructions)
                        VALUES ('$user_id', '$bio', '$instruct')";
        if (mysqli_query($mysqli, $sqlinsert)) {
            echo "New record created successfully"."<br/>";
            echo "<script> location.href='add_items.php'; </script>";
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
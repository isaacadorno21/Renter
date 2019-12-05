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
<h1>My Account</h1>
<p>Edit My Account</p>
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
    
<?php
    if (isset($_POST['sign_out'])) {
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
        echo "<script> location.href='index.html'; </script>";
    }
?>
    
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
    
     //PREVIEW OF ACCOUNT DETAILS----------------------------------------------
         $sqldetails = "SELECT * FROM User WHERE $cur_user_id = User.user_id";
            if ($result = mysqli_query($mysqli, $sqldetails)) {
             echo "<div style='margin-left:5%;'><h3><b>"."Account Details"."</b></h3>"; 
             echo "<div style='border:solid 1px grey;width:97%;'>";
              echo "<table>";
                echo "<tr>
                <div style='margin-left=15%;'>
                <th>Username</th>
                <th>Full Name</th>
                <th>City</th>
                <th>Phone Number</th>
                <th>Password</th>
                <th>Other</th>
                </tr>"; 
                /* fetch associative array */
                while ($row = mysqli_fetch_assoc($result)) {
                    $user_name = $row["username"];
                    $fullname= $row["full_name"];
                    $city = $row["city"];
                    $phone = $row["phone_number"];

                       echo "<tr>
                       <td>".$user_name."</td>
                       <td>".$fullname."</td>
                       <td>".$city."</td>
                       <td>".$phone."</td>
                       <td><button class='btn'><a href=\"passwordredirect.php?user=$cur_user_id\">Change Password</a></button></td>
                       <td><button class='btn'><a href=\"accountredirect.php?user=$cur_user_id\">Edit Account</a></button></td>
                       </tr>";
        
    
                }
              echo "</table>";
              echo "</div></div>";
            }
            else {
                echo "Account details failed to display"."<br>";
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
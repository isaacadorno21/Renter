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
<h1>Change Password</h1>
</header>

<!-- Main -->



<div id="main"><!-- Content -->


<!-- SHOW CURRENT PASSWORD -->
    <div style="margin-left:5%; margin-right:5%;">
        <br>
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
            
            $sqlpassword = "SELECT password FROM User WHERE User.user_id = $cur_user_id";
            if ($result = mysqli_query($mysqli, $sqlpassword)) {
                        while ($row = mysqli_fetch_assoc($result)){
                                    $userpassword = $row['password'];
                                }
                        echo "Original Password: $userpassword";
                    }
                    else {
                        echo "Password failed to update";
                    }
            
        ?>
    </div>

     <div style="margin-left:5%; margin-right:5%;">
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
    
        New Password:<br>
        <input type="text" name="password">
        <br>
        
        <input type="submit" value="Update" name="submit"><br></br>
        <input type="button" value="Back" onclick="window.location='/account.php';">
    </form> 
    </div>
    
    
     <div style="margin-left:5%; margin-right:5%;">
        
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
        
            $userpassword = $_POST['password'];
            
            if(isset($_POST['submit'])) {
                
                 //UPDATE PASSWORD-----------------------------------------------------
                if (!empty($userpassword)) {
                    $sqlinsert = "UPDATE User SET password='$userpassword' WHERE $cur_user_id = User.user_id";
                    if (mysqli_query($mysqli, $sqlinsert)) {
                               echo "User's password changed to "."$userpassword".".<br/>";
                    }
                    else {
                        echo "User's password failed to update";
                    }
                }
                
                 //PREVIEW OF ACCOUNT DETAILS----------------------------------------------
                 $sqldetails = "SELECT * FROM User WHERE $cur_user_id = User.user_id";
                    if ($result = mysqli_query($mysqli, $sqldetails)) {
                      echo "<table>";
                        echo "<tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>City</th>
                        <th>Phone Number</th>
                        <th>Password</th>
                        </tr>"; 
                        /* fetch associative array */
                        while ($row = mysqli_fetch_assoc($result)) {
                            $user_name = $row["username"];
                            $fullname= $row["full_name"];
                            $city = $row["city"];
                            $phone = $row["phone_number"];
                            $password = $row["password"];
                            
                               echo "<tr><td>".$user_name."</td><td>".$fullname."</td><td>".$city."</td><td>".$phone."</td><td>".$password."</td></tr>";
            
                        }
                      echo "</table>";
                    }
                    else {
                        echo "Account details failed to display"."<br>";
                    }
            }
        
        ?>
        </div>
<br><br>



</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">Created by UIUC CS411 students - Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
<?php 
  session_start();
?>

<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Log In - Stellar by HTML5 UP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>Login</h1>
<p>Login Page</p>
</header>

<!-- Main -->

<div id="main"><!-- Content -->

<div style="margin-left: 15%; ">
    <h2>Login Page</h2>
    
    <form>
        <div style="width: 80%;">
        Username:<br>
        <input type="text" name="username">
        <br>
        Password:<br>
        <input type="password" name="password">
        <br>
        <input type="submit" value="Log In">
        &nbsp; &nbsp;
        <input type="button" value="Cancel" onclick="window.location='/index.html';">  
        </div>
        <br>
        Don't have an account?<br>
        <input type ="button" value = "Sign Up" onclick="window.location='/sign_up.php';">
        <br></br>
        </form> 
</div>
<?php

    $cur_username = $_GET["username"];
    $cur_password = $_GET["password"];
    
    if ($cur_username != null && $cur_password != null) {
        
        // Create connection
        $servername = "localhost";
        $username = "uiucrenter_mainuser";
        $password = "uiucrenter_mainuser";
        $database = "uiucrenter_main";
        $mysqli = mysqli_connect($servername, $username, $password, $database);
        
        if (mysqli_connect_errno($mysqli)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        // Create query        
        $sqlquery = "SELECT User.user_id FROM User WHERE User.username = '$cur_username' AND User.password = '$cur_password'";
        $result = mysqli_query($mysqli, $sqlquery); 
        
        if (mysqli_num_rows($result) == 0) { 
            echo "<div style='margin-left: 15%;'>";
            echo "Account not found. Please try again or sign up.";
            echo "</div><br>";
        }
        
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                $user_id = $row["user_id"];
            }
            $_SESSION['username'] = $cur_username;
            $_SESSION['user_id'] = $user_id;
            echo "<script> location.href='home_page.php'; </script>";
            exit;
        }
    }
?>

</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">CS411 Fall 2019 Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
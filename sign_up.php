<?php 
  session_start();
?>

<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Sign Up - Stellar by HTML5 UP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>Sign Up</h1>
<p>Create an account today!</p>
</header>

<!-- Main -->

<div id="main"><!-- Content -->

<div style="margin-left: 15%; ">
    <br>
    <h3>Please fill in your information below.</h3>

    <form>
        <div style="width: 80%;">
            Username:<br>
            <input type="text" name="username">
            <br>
            Password:<br>
            <input type="password" name="password">
            <br>
            Full Name:<br>
            <input type="text" name="full_name">
            <br>
            Phone Number:<br>
            <input type="text" name="phone_number">
            <br>
            <p>
            <?php
                $location_arr = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
                $latitude = $location_arr['geoplugin_latitude'];
                $longitude = $location_arr['geoplugin_longitude'];
                $city_name = $location_arr['geoplugin_city'];
                echo "Location: $city_name ($latitude, $longitude)";
            ?>
            </p>
            <input type="submit" value="Sign Up"> <br></br>
            <input type="button" value="Cancel" onclick="window.location='/index.html';">
            <br></br>
        </div>
    </form> 
</div>

<?php
    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";
    
    $cur_username = $_GET["username"];
    $cur_password = $_GET["password"];
    $full_name = $_GET["full_name"];
    //$city = $_GET["city"];
    $phone_number = $_GET["phone_number"];
    
    
    $location_arr = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
    $latitude = $location_arr['geoplugin_latitude'];
    $longitude = $location_arr['geoplugin_longitude'];
    $city = $location_arr['geoplugin_city'];
    
    
    // Create connection
    $mysqli = mysqli_connect($servername, $username, $password, $database);
    
    if (mysqli_connect_errno($mysqli)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // Create query        
    $sqlquery = "SELECT * FROM User WHERE User.username = '$cur_username'";
    $result = mysqli_query($mysqli, $sqlquery); 
    
    if (mysqli_num_rows($result) == 1) { 
        echo "Username already exists. Please try again.";
    }
    else {
        if (!empty($cur_username) && !empty($cur_password) && !empty($full_name) && !empty($city) && !empty($phone_number)) {
            $sqlinsert = "INSERT INTO User (username, password, full_name, phone_number, city, latitude, longitude)
                        VALUES ('$cur_username', '$cur_password', '$full_name', '$phone_number', '$city', '$latitude', '$longitude')";
                        
            if (mysqli_query($mysqli, $sqlinsert)) {
                   echo "New record created successfully"."<br/>";
            }
            
            $sqlquery = "SELECT User.user_id FROM User WHERE User.username = '$cur_username'";
            $result = mysqli_query($mysqli, $sqlquery); 
            
            while ($row = mysqli_fetch_assoc($result)) {
                $user_id = $row["user_id"];
            }
            
            $_SESSION['username'] = $cur_username;
            $_SESSION['user_id'] = $user_id;
            echo "<script> location.href='home_page.php'; </script>";
            exit;
        }
        else {

          //  echo "<div style='margin-left: 15%;'>"."Please fill all the required fields."."</div><br>";
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
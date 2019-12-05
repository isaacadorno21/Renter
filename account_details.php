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
<h1>My Account Details</h1>
<p>Edit Account Details</p>
</header>

<!-- Main -->



<div id="main"><!-- Content -->

    <br></br>
    <div style="margin-left:5%; margin-right:5%;">
        <h2>Update Fields.</h2>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        
            Username:<br>
            <input type="text" name="user_name">
            <br>
            
            Full Name:<br>
            <input type="text" name="full_name">
            <br>
            
            City:<br>
            <input type="text" name="city">
            <br>
            
            Phone:<br>
            <input type="text" name="phone_number">
            <br>
    
            <input type="submit" value="Update" name="submit"><br></br>
            <input type="button" value="Back" onclick="window.location='/account.php';">
    
        </form> 
    </div>
    
    
<div style='margin-left:5%; margin-right:5%;'>  
<?php
    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";

    $cur_user_id = $_SESSION['user_id'];

    $user_name = $_POST["user_name"];
    $fullname = $_POST["full_name"];
    $city = $_POST["city"];
    $phone = $_POST["phone_number"];
    $userpassword = $_POST["password"];
    
    // Create connection
    $mysqli = mysqli_connect($servername, $username, $password, $database);
    
    if (mysqli_connect_errno($mysqli)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    //UPLOAD IMAGE---------------------------------------------------------
    $targetDir = "images/item_photos/";
    $fileName = $_FILES['pic']['name'];
    $fileTmpName  = $_FILES['pic']['tmp_name'];
    $fileType = $_FILES['pic']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $uploadPath = $targetDir . basename($fileName); 
    
    $fileExtensions = ['jpeg','jpg','png', 'gif'];

    if(isset($_POST['submit'])) {
        
        //UPDATE USERNAME-----------------------------------------------------
        if (!empty($user_name)) {
            $sqlinsert = "UPDATE User SET username ='$user_name' WHERE $cur_user_id = User.user_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "User's name updated to '"."$user_name"."'.<br/>";
            }
            else {
                echo "User's name failed to update";
            }
        }
        
        //UPDATE FULL NAME-----------------------------------------------------
        if (!empty($fullname)) {
            $sqlinsert = "UPDATE User SET full_name ='$fullname' WHERE $cur_user_id = User.user_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "User's full name updated to "."$fullname".".<br/>";
            }
            else {
                echo "User's name failed to update";
            }
        }
        
        //UPDATE FULL CITY-----------------------------------------------------
        if (!empty($city)) {
            $sqlinsert = "UPDATE User SET city ='$city' WHERE $cur_user_id = User.user_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "User's city updated to "."$city".".<br/>";
            }
            else {
                echo "User's city failed to update";
            }
        }
        
         //UPDATE PHONE-----------------------------------------------------
        if (!empty($phone)) {
            $sqlinsert = "UPDATE User SET phone_number='$phone' WHERE $cur_user_id = User.user_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "User's  phone updated to "."$phone".".<br/>";
            }
            else {
                echo "User's Phone failed to update";
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
                    $password = 'hidden';
                    
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
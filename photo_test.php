<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Photo Test - Stellar by HTML5 UP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>Photo Test</h1>
</header>

<!-- Main -->

<div id="main"><!-- Content -->

<h2>Photo Test Page</h2>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
  Upload a Photo:<br>
  <input type="file" name="pic" id="pic">
  <input type="submit" value="Upload Image" name="submit">
<br>
</form>

<?php
    
    //All the image upload stuff
    
    $targetDir = "images/item_photos/";
    $fileName = $_FILES['pic']['name'];
    $fileTmpName  = $_FILES['pic']['tmp_name'];
    $fileType = $_FILES['pic']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    $uploadPath = $targetDir . basename($fileName); 
    
    $fileExtensions = ['jpeg','jpg','png', 'gif'];
    
    /*
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        
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
    }
    */
    
    // Add that info to the table
    
    if(isset($_POST["submit"])) {
        
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
        $username = "uiucrenter_testuser";
        $password = "uiucrenter_testuser";
        $database = "uiucrenter_test";
        
        // Create connection
        $mysqli = mysqli_connect($servername, $username, $password, $database);
        
        if (mysqli_connect_errno($mysqli)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $sqlinsert = "INSERT INTO photo_test (photo)
                            VALUES ('$fileName')";
        if (mysqli_query($mysqli, $sqlinsert)) {
            echo "New record created successfully"."<br/>";
        }
            
        $sqlquery = "SELECT * FROM photo_test";
        if ($result = mysqli_query($mysqli, $sqlquery)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $photo = $row["photo"];
                echo "<img src='images/item_photos/" . $photo . "' alt='error'>";
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
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
<p>Update Product Status</p>
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
<!--Display Item Details-->
    <?php
       $servername = "localhost";
        $username = "uiucrenter_mainuser";
        $password = "uiucrenter_mainuser";
        $database = "uiucrenter_main";
    
        $cur_user_id = $_SESSION['user_id'];
        $cur_item = $_SESSION['item_id'];
    
    
         $item_name = $_POST["item_name"];
         $cost = $_POST["cost"];
         $description = $_POST["description"];
         $pic = $_POST["pic"];

      // Create connection
     $mysqli = mysqli_connect($servername, $username, $password, $database);
    
      if (mysqli_connect_errno($mysqli)) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
     }
     $sqlcurritem = "SELECT item_name FROM Items WHERE $cur_item = Items.item_id";
    if ($result = mysqli_query($mysqli, $sqlcurritem)) {
            /* fetch associative array */
            while ($row = mysqli_fetch_assoc($result)) {
                $item = $row["item_name"];
                echo "<div style='text-align: center'><h3><b>Updating Item: $item"."</b></h3></div>";
            }
    }
    else{
        echo "No Items Found";
    }
    ?>
    
<!--Display Form-->
<div style="margin-left: 15%; ">
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
            <div style="width: 80%;">
            Change Photo: <br>
            <input type="file" name="pic" id="pic">
            <br>
            <br>
            
            Update Item Name:<br>
            <input type="text" name="item_name" >
            <br>
            Update Cost per day:<br>
            <input type="text" name="cost">
            <br>

            Update Description:<br>
            <input type="text" name="description">
            <br>
            </div>

            <input type="submit" value="Update" name="submit"> <br></br>
            <input type="button" value="Cancel" onclick="window.location='/my_items.php';">
        </form> 
        <br>
    </div>
</div>
    
<?php
    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";

    $cur_user_id = $_SESSION['user_id'];
    $cur_item = $_SESSION['item_id'];


     $item_name = $_POST["item_name"];
     $cost = $_POST["cost"];
     $description = $_POST["description"];
     $pic = $_POST["pic"];

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
        
        //UPLOAD PHOTO--------------------------------------------------------
       if (! in_array($fileExtension,$fileExtensions)) {
            $errors[] = "This file extension is not allowed. Please upload an image.";
        }
        
        else {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            if ($didUpload) {
               // echo "The file " . basename($fileName) . " has been uploaded"."<br/>";
            } else {
                echo "An error occurred somewhere. Try again or contact the admin";
            }
        }
        //UPDATE PHOTO --------------------------------------------------------
          if (!empty($fileName)) {
            $sqlinsert = "UPDATE Items SET photo ='$fileName' WHERE $cur_item = Items.item_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "Item's photo updated to "."$fileName"."<br/>";
            }
            else {
                echo "Item's name failed to update";
            }
            
             
         //PREVIEW PHOTO--------------------------------------------------------
            $sqlquery = "SELECT photo FROM Items WHERE Items.item_id = $cur_item";
            if ($result = mysqli_query($mysqli, $sqlquery)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $photo = $row["photo"];
                    echo "<img src='images/item_photos/" . $photo . "' alt='error'>";
                }
            }   
            
        }
        
        //UPDATE ITEM NAME-----------------------------------------------------
        if (!empty($item_name)) {
            $sqlinsert = "UPDATE Items SET item_name ='$item_name' WHERE $cur_item = Items.item_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "Item's name updated to '"."$item_name"."'.<br/>";
            }
            else {
                echo "Item's name failed to update";
            }
        }
        
        //UPDATE ITEM COST-----------------------------------------------------
        if (!empty($cost)) {
            $sqlinsert = "UPDATE Items SET cost ='$cost' WHERE $cur_item = Items.item_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "Item's cost updated to $"."$cost".".<br/>";
            }
            else {
                echo "Item's cost failed to update";
            }
        }
        
        //UPDATE ITEM DESCRIPTION----------------------------------------------
        if (!empty($description)) {
            $sqlinsert = "UPDATE Items SET description ='$description' WHERE $cur_item = Items.item_id";
            if (mysqli_query($mysqli, $sqlinsert)) {
                       echo "Item's description updated to '" ."$description". ".'<br/>";
            }
            else {
                echo "Item's description failed to update";
            }
        }
        
        
        //PREVIEW OF ITEM DETAILS----------------------------------------------
         $sqldetails = "SELECT * FROM Category JOIN Items ON Category.category_id = Items.category_id WHERE $cur_item = Items.item_id";
            if ($result = mysqli_query($mysqli, $sqldetails)) {
              echo "<table>";
                echo "<tr>
                <th>Photo</th>
                <th>Item Name</th>
                <th>Cost</th>
                <th>Description</th>
                <th>Category</th>
                </tr>"; 
                /* fetch associative array */
                while ($row = mysqli_fetch_assoc($result)) {
                    $item_id = $row["item_id"];
                    $photo = $row["photo"];
                    $photo_full = "<img src='images/item_photos/" . $photo . "' alt='error' height='50' width='50'>";
                    $item_name = $row["item_name"];
                    $cost = $row["cost"];
                    $description = $row["description"];
                    $category = $row["category_name"];
                    
                       echo "<tr><td>".$photo_full."</td><td>".$item_name."</td><td>".'$'.$cost."</td><td>".$description."</td><td>".$category."</td></tr>";
    
                }
              echo "</table>";
            }
            else {
                echo "Item details failed to display";
            }
    
    }

?>

</section>
</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">CS411 Fall 2019 Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
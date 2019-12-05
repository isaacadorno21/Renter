<?
session_start(); 
$_SESSION['item_id']=$_GET['item'];
$itemid = $_SESSION['item_id'];
//remove item from loans and items table
    $servername = "localhost";
    $username = "uiucrenter_mainuser";
    $password = "uiucrenter_mainuser";
    $database = "uiucrenter_main";
    
    $cur_user_id = $_SESSION['user_id'];
    $search_query = $_GET["search_query"];
    
    // Create connection
    $mysqli = mysqli_connect($servername, $username, $password, $database);
    
    if (mysqli_connect_errno($mysqli)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    //delete from loans
    $sqldelete = "DELETE FROM Loans WHERE item_id = $itemid";
    
    if($result = mysqli_query($mysqli, $sqldelete)){
        //success
    }
    else{
        //failed to delete
    }
    //delete from items
    $sqldelete = "DELETE FROM Items WHERE item_id = $itemid";
    
    if($result = mysqli_query($mysqli, $sqldelete)){
           echo "<script>alert('Successfully deleted!');</script>";
    }
    else{
        //failed to delete
    }
echo "<script> location.href='my_items.php'; </script>";
?>
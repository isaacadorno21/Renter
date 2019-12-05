<?
session_start(); 
$_SESSION['item_id']=$_GET['item'];
$itemid = $_SESSION['item_id'];
//remove item from loans table
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
    
    //delete
    $sqldelete = "DELETE FROM Loans WHERE item_id = $itemid";

    require_once "./SleekDB-master/src/SleekDB.php";
    if($result = mysqli_query($mysqli, $sqldelete)){
        // UPDATE return_date in sleekdb
        
        //$date = date('Y-m-d');
        //$updateable = [
        //    'Relationships' => [
        //        'return_date' => $date
        //    ]
        //];
        //$usersDB->where('item_id', '=', $itemid)->update($updateable);
        echo "<script>alert('Successfully returned!');</script>";
    }
    else{
        //failed to delete
    }

echo "<script> location.href='loaned_items.php'; </script>";
?>
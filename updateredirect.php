<?
session_start(); 
$_SESSION['item_id']=$_GET['item'];
echo "<script> location.href='update.php'; </script>";
?>
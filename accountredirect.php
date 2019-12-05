<?
session_start(); 
$_SESSION['user_id']=$_GET['user'];
echo "<script> location.href='account_details.php'; </script>";
?>
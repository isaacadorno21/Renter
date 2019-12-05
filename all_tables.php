<!DOCTYPE HTML>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>All Tables - Stellar by HTML5 UP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link href="assets/css/main.css" rel="stylesheet" /><noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload"><!-- Wrapper -->
<div id="wrapper"><!-- Header -->
<header id="header">
<h1>View Table</h1>

<p>Library of Current Data</p>
</header>

<!-- Nav -->

<nav id="nav">
<ul>
	<li><a href="index.html">Home</a></li>
	<li><a href="generic.php">View Test Table</a></li>
	<li><a href="search.php">Search</a></li>
	<li><a href="insert.php">Insert</a></li>
	<li><a href="delete.php">Delete</a></li>
	<li><a href="update.php">Update</a></li>
	<li><a class="active" href="all_tables.php">View All Tables</a></li>
</ul>
</nav>

<!-- Main -->

<div id="main"><!-- Content -->
<section class="main" id="content">

<h2>VIEW ALL TABLEs</h2>

<?php /*
$servername = "localhost";
$username = "uiucrenter_testuser";
$password = "uiucrenter_testuser";
$database = "uiucrenter_test";

// Create connection
$mysqli = mysqli_connect($servername, $username, $password, $database);

if (mysqli_connect_errno($mysqli)) {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sqlquery = "SELECT * FROM test1";
if ($result = mysqli_query($mysqli, $sqlquery)) {
        echo "<table>";
        echo "<tr>
        <th>name</th>
        <th>cost</th>
        <th>status</th>
        <th>owner</th>
        </tr>"; 
        fetch associative array
        while ($row = mysqli_fetch_assoc($result)) {
        $name = $row["name"];
        $cost = $row["cost"];
        $status = $row["status"];
        $owner = $row["owner"];
       
        echo "<tr><td>".$name."</td><td>".$cost."</td><td>".$status."</td><td>".$owner."</td></tr>";
        }
        echo "</table>";
        echo 'Total results: ' . $result->num_rows;
}
*/?>
</section>
</div>
<!-- Footer -->

<footer id="footer">

<p class="copyright">CS411 Fall 2019 Renter</p>
</footer>
</div>
<!-- Scripts --><script src="assets/js/jquery.min.js"></script><script src="assets/js/jquery.scrollex.min.js"></script><script src="assets/js/jquery.scrolly.min.js"></script><script src="assets/js/browser.min.js"></script><script src="assets/js/breakpoints.min.js"></script><script src="assets/js/util.js"></script><script src="assets/js/main.js"></script></body>
</html>
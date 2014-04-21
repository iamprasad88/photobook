<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "dbproject";

function dbConnect() {
	global $dbhost, $dbuser, $dbpass, $db;

	$con = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

	if (mysqli_connect_errno($con)) {
		echo "Failed to connect to database: " . mysqli_connect_error();
	}
	return $con;
}

function dbClose($con) {
	mysqli_close($con);
}

{

}
?>
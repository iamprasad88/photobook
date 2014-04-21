<?php
include_once 'queries.php';
session_start();

$uname = key_exists('uname', $_POST) ? $_POST['uname'] : "";
$pwd = key_exists('pwd', $_POST) ? $_POST['pwd'] : "";

$error = array('errorcode' => 0);
$error['errormsg'] = "";
if ($uname == "") {
	$error['errorcode'] = 1;
	$error['errormsg'] .= "<li>User Name cannot be empty</li>";
}
if ($pwd == "") {
	$error['errorcode'] = 1;
	$error['errormsg'] .= "<li>Password cannot be empty</li>";
}

if ($error['errorcode'] == 0) {
	$errmsg = "";
	if (validate_user($uname, $pwd) == FALSE) {
		$error['errorcode'] = 2;
		$error['errormsg'] = "Username and or Password is incorrect";
	} else {
		$_SESSION['uname'] = $uname;
		$_SESSION['EXPIRES'] = time()+360;
	}
}

echo json_encode($error);
?>
<?php
include_once 'queries.php';

$uname = key_exists('uname', $_POST) ? $_POST['uname'] : "";
$pwd = key_exists('pwd', $_POST) ? $_POST['pwd'] : "";
$fname = key_exists('fname', $_POST) ? $_POST['fname'] : "";
$lname = key_exists('lname', $_POST) ? $_POST['lname'] : "";
$gender = key_exists('gender', $_POST) ? $_POST['gender'] : "";
$email = key_exists('email', $_POST) ? $_POST['email'] : "";

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
if ($fname == "") {
	$error['errorcode'] = 1;
	$error['errormsg'] .= "<li>First Name cannot be empty</li>";
}
if ($email == "") {
	$error['errorcode'] = 1;
	$error['errormsg'] .= "<li>Email cannot be empty</li>";
}
if ($error['errorcode'] == 0) {
	$errmsg = "";
	if (create_user($uname, $pwd, $fname, $lname, $email, $gender, $errmsg) == FALSE) {
		$error['errorcode'] = 2;
		$error['errormsg'] = $errmsg;
	} else {
		if (mkdir("../users/$uname") == FALSE) {
			$error['errorcode'] = 2;
			$error['errormsg'] = $errmsg;
		} else {
			mkdir("../users/$uname/temp");
		}
	}
}

echo json_encode($error);
?>
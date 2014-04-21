<?php
include_once 'queries.php';

	$uname = key_exists('uname', $_POST) ? $_POST['uname'] : "";
	$pwd = key_exists('pwd', $_POST) ? $_POST['pwd'] : "";
	$fname = key_exists('fname', $_POST) ? $_POST['fname'] : "";
	$lname = key_exists('lname', $_POST) ? $_POST['lname'] : "";
	$gender = key_exists('gender', $_POST) ? $_POST['gender'] : "";
	$email = key_exists('email', $_POST) ? $_POST['email'] : "";
	$country = key_exists('country', $_POST) ? $_POST['country'] : "";
	$language = key_exists('language', $_POST) ? $_POST['language'] : "";


	$error = array('errorcode' => 0);
	$error['errormsg'] = "";
	if ($uname == "") {
		$error['errorcode'] = 1;
		$error['errormsg'] .= "User Name cannot be empty";
	}
	if ($pwd == "") {
		$error['errorcode'] = 1;
		$error['errormsg'] .= "Password cannot be empty";
	}
	if ($fname == "") {
		$error['errorcode'] = 1;
		$error['errormsg'] .= "First Name cannot be empty";
	}
	if ($email == "") {
		$error['errorcode'] = 1;
		$error['errormsg'] .= "Email cannot be empty";
	}
	if ($gender == "") {
		$error['errorcode'] = 1;
		$error['errormsg'] .= "Pick a gender";
	}	

	if ($error['errorcode'] == 0) {
		$errmsg = "";
		if (update_user($uname, $pwd, $fname, $lname, $email, $gender, $language, $country, $errmsg) == FALSE) {
			$error['errorcode'] = 2;
			$error['errormsg'] = $errmsg;
		} 
	}



	echo json_encode($error);

?>
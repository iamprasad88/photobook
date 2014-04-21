<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$pin_id = key_exists('pin_id', $_POST) ? $_POST['pin_id'] : "";
$message = key_exists('message', $_POST) ? $_POST['message'] : "";


$data = array('errorcode' => 0);
$data['errormsg'] = "";

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if (strlen($message) > 1000) {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Message must be less than 1000 Characters</p>";
}
if ($message == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Message is empty</p>";
}

if ($data['errorcode'] == 0) {
	$errmsg = "";
	if (create_comment($uname,$message,$pin_id,$comment_id,$err_msg) == FALSE) {
		$data['errorcode'] =1;
		$data['errormsg'] .= "<p class='ui-error'>$err_msg</p>";
	}
}

echo json_encode($data);
?>
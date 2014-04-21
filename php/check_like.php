<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$pin_id = key_exists('pin_id', $_POST) ? $_POST['pin_id'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if ($pin_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Pin cannot be empty</p>";
}

if ($data['errorcode'] == 0) {
	$errmsg = "";
	if(!get_if_liked($uname,$pin_id,$liked)){
		$data['errorcode'] =1;
		$data['errormsg'] .= $err_msg;
	}
}

$data['liked'] = $liked;

echo json_encode($data);
?>
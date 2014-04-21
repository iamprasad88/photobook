<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$pin_id = key_exists('pin_id', $_POST) ? $_POST['pin_id'] : "";
$pic_id = key_exists('pic_id', $_POST) ? $_POST['pic_id'] : "";
$root_pin_id = key_exists('root_pin_id', $_POST) ? $_POST['root_pin_id'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if ($pic_id == "") {
	$data['errormsg'] .= "<p class='ui-info'>Picture is empty</p>";
}
if ($pin_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Pin cannot be empty</p>";
}
if ($root_pin_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Root Pin cannot be empty</p>";
}

if ($data['errorcode'] == 0) {
	$errmsg = "";
	if (toggle_like ($uname,$pin_id,$root_pin_id,$pic_id,$err_msg) == FALSE) {
		$data['errorcode'] =1;
		$data['errormsg'] .= $err_msg;
	}
	else {
		if(!get_if_liked($uname,$pin_id,$liked)){
			$data['errorcode'] =1;
			$data['errormsg'] .= $err_msg;
		}
	}
}
$data['liked'] = $liked;

echo json_encode($data);
?>
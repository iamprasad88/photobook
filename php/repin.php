<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$tags =  key_exists('tags', $_POST) ? $_POST['tags'] : "";
$title = key_exists('title', $_POST) ? $_POST['title'] : "";

$pinboard_id = key_exists('pinboard_id', $_POST) ? $_POST['pinboard_id'] : "";
$root_pin_id = key_exists('root_pin_id', $_POST) ? $_POST['root_pin_id'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";
$pin_id = "";
$pic_id = "";

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if ($title == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Title cannot be empty</p>";
}
if (strlen($title) > 50) {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Title must be less than 50 Characters</p>";
}
if ($pinboard_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Pinboard cannot be empty</p>";
}
if ($root_pin_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Pin cannot be empty</p>";
}
if ($tags == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Tags cannot be empty</p>";
}

if (strlen($tags) > 1000) {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Title must be less than 1000 Characters</p>";
}

if ($data['errorcode'] == 0) {
	$errmsg = "";
	if (create_repin($root_pin_id,$uname,$tags,$title,$pinboard_id,$pic_id,$pin_id,$err_msg) == FALSE) {
		$data['errorcode'] =2;
		$data['errormsg'] .= $errmsg;
	} else {
		$data['errorcode'] = 0;
		$data['pin_id'] = $pin_id;
	}
}

echo json_encode($data);
?>
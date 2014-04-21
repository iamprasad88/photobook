<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$comment_privacy =  key_exists('comment_privacy', $_POST) ? $_POST['comment_privacy'] : "";
$name = key_exists('name', $_POST) ? $_POST['name'] : "";
$description = key_exists('description', $_POST) ? $_POST['description'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if (strlen($description) > 1000) {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Description must be less than 1000 Characters</p>";
}
if (strlen($description) == "") {
	$data['errormsg'] .= "<p class='ui-info'>Description is empty</p>";
}
if ($name == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Board Name cannot be empty</p>";
}
if ($comment_privacy == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Comment Privacy Settings cannot be empty</p>";
}

if ($data['errorcode'] == 0) {
	if (create_board($uname,$name,$description,$comment_privacy,$board_id,$err_msg) == FALSE) {
		$data['errorcode'] = 1;
		$data['errormsg'] .= "<p class='ui-error'>$err_msg</p>";
	}
}

echo json_encode($data);
?>
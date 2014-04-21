<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$comment_privacy =  key_exists('comment_privacy', $_POST) ? $_POST['comment_privacy'] : "";
$name = key_exists('name', $_POST) ? $_POST['name'] : "";
$stream_id = key_exists('stream_id', $_POST) ? $_POST['stream_id'] : "";
$board_id = key_exists('board_id', $_POST) ? $_POST['board_id'] : "";
$description = key_exists('description', $_POST) ? $_POST['description'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";

if ($stream_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Stream cannot be empty</p>";
}
if ($board_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Board cannot be empty</p>";
}

if ($data['errorcode'] == 0) {
	if (follow_board($stream_id,$board_id,$err_msg) == FALSE) {
		$data['errorcode'] = 1;
		$data['errormsg'] .= "<p class='ui-error'>$err_msg</p>";
	}
}

echo json_encode($data);
?>
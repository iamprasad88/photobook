<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$board_id = key_exists('board_name', $_POST) ? $_POST['board_name'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if ($board_id == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Board Name cannot be empty</p>";
}

if ($data['errorcode'] == 0) {
	if (delete_board($uname,$board_id,$rows_affected,$err_msg) == FALSE) {
		$data['errorcode'] = 1;
		$data['errormsg'] .= "<p class='ui-error'>$err_msg</p>";
	}
}

echo json_encode($data);
?>
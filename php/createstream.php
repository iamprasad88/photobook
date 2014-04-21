<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$keyword =  key_exists('keyword', $_POST) ? $_POST['keyword'] : "";
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
if ($name == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Stream Name cannot be empty</p>";
}
if ($keyword == "") {
	$data['errormsg'] .= "<p class='ui-info'>Keyword Query is empty</p>";
}

if ($data['errorcode'] == 0) {
	if (create_stream($uname,$name,$description,$keyword,$stream_id,$err_msg) == FALSE) {
		$data['errorcode'] = 1;
		$data['errormsg'] .= "<p class='ui-error'>$err_msg</p>";
	}
}

echo json_encode($data);
?>
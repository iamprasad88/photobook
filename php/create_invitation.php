<?php
include_once 'queries.php';
require_once '../views/access.php';


$user = $_SESSION['uname'];
$friend = key_exists('invited_user', $_POST) ? $_POST['invited_user'] : "";
$message = key_exists('Message', $_POST) ? $_POST['Message'] : "";

$error = array('errorcode' => 0);
$error['errormsg'] = "";
if ($user == "")
{
	$error['errorcode'] = 1;
	$error['errormsg'] = "User must be logged in";
}
if ($friend == "")
{
	$error['errorcode'] = 1;
	$error['errormsg'] = "Friend name cannot be empty";
}
if ($error['errorcode'] == 0) 
{
	$errmsg = "";
	if (invite_friend($user, $friend, $message, $errmsg) == FALSE) 
	{
		$error['errorcode'] = 2;
		$error['errormsg'] = $errmsg;
	} 
}

echo json_encode($error);




?>
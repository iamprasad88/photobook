<?php
include_once 'queries.php';
require_once '../views/access.php';


$user = $_SESSION['uname'];
$friend = key_exists('invited_user', $_POST) ? $_POST['invited_user'] : "";

$error = array('errorcode' => 0);
$error['errormsg'] = "";
if ($user == "")
{
	$error['errorcode'] = 1;
	$error['errormsg'] .= "<li>User must be logged in</li>";
}
if ($friend == "")
{
	$error['errorcode'] = 1;
	$error['errormsg'] .= "<li>Friend name cannot be empty</li>";
}

if ($error['errorcode'] == 0) 
{
	$errmsg = "";
	if (accept_request($user, $friend, $errmsg) == FALSE) 
	{
		$error['errorcode'] = 2;
		$error['errormsg'] = $errmsg;
	} 
}

echo json_encode($error);




?>
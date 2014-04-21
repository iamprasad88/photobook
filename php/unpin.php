<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$pin_id =  key_exists('pin_id', $_POST) ? $_POST['pin_id'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";
$data['pin_id'] = $pin_id;

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if ($pin_id == "") {
	$data['errormsg'] .= "<p class='ui-info'>Pin is empty</p>";
}

if ($data['errorcode'] == 0) {
	$errmsg = "";
	if (unpin($pin_id,$uname,$pic_id,$pic_fname,$root_pin_id,$err_msg) == FALSE) {
		$data['errorcode'] = 2;
		$data['errormsg'] .= "<p class='ui-error'>$err_msg</p>";
	} else {
		//copy file to images folder here
		if($pin_id==$root_pin_id){
			// unlink("/../images/$pic_fname");
		}
	}
}

echo json_encode($data);
?>
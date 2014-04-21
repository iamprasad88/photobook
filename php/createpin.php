<?php
session_start();
include_once 'queries.php';

$uname = key_exists('uname', $_SESSION) ? $_SESSION['uname'] : "";
$tags =  key_exists('tags', $_POST) ? $_POST['tags'] : "";
$url_pic =  key_exists('url_pic', $_POST) ? $_POST['url_pic'] : "";
$url_site =  key_exists('url_site', $_POST) ? $_POST['url_site'] : "";
$title = key_exists('title', $_POST) ? $_POST['title'] : "";
$description = key_exists('description', $_POST) ? $_POST['description'] : "";
$pinboard_id = key_exists('pinboard_id', $_POST) ? $_POST['pinboard_id'] : "";
$path = key_exists('file_pic', $_FILES) ? $_FILES['file_pic']['name']: "";
$file_ext = pathinfo($path, PATHINFO_EXTENSION);
$upload_method = key_exists('upload_method', $_POST) ? $_POST['upload_method'] : "";

$data = array('errorcode' => 0);
$data['errormsg'] = "";
$pin_id = "";
$pic_id = "";

if ($uname == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>User account is logged out</p>";
}
if (strlen($description) > 1000) {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Description must be less than 1000 Characters</p>";
}
if ($description == "") {
	$data['errormsg'] .= "<p class='ui-info'>Description is empty</p>";
}
if (strlen($url_pic) > 1000) {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>URL of image must be less than 1000 Characters</p>";
}
if (strlen($url_site) > 1000) {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>URL of site must be less than 1000 Characters</p>";
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
if ($upload_method == "") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>upload_method cannot be empty</p>";
}
if ($upload_method == "file" && $path=="") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>File cannot be empty</p>";
}
if ($upload_method == "url" && $url_pic=="") {
	$data['errorcode'] = 1;
	$data['errormsg'] .= "<p class='ui-error'>Picture URL cannot be empty</p>";
}
if ($upload_method == "url" && $url_site=="") {
	$data['errormsg'] .= "<p class='ui-info'>Site URL is empty</p>";
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
	if (create_pin($uname,$tags,$url_pic,$url_site,$title,$description,$pinboard_id,$file_ext,$pic_id,$pin_id,$err_msg) == FALSE) {
		$data['errorcode'] =1;
		$data['errormsg'] .= $errmsg;
	} else {
		//copy file to images folder here

		if($upload_method=="url")
		{
			function test_input($data)
			{
			     $data = trim($data);
			     $data = stripslashes($data);
			     $data = htmlspecialchars($data);
			     return $data;
			}
			$location = "../images/".$pic_id.".".$file_ext;
			$website = test_input($url_pic);
			// check if URL address syntax is valid (this regular expression also allows dashes in the URL)
			if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website))
			{
				$data['errorcode'] =1;
				$data['errormsg'] .="<p class='ui-error'>Invalid URL</p>";
			}
			elseif(@exif_imagetype($url_pic))
			{
				file_put_contents($location, file_get_contents($url_pic));
			}
			else 
			{
				$data['errorcode'] =1;
				$data['errormsg'] .="<p class='ui-error'>File type not supported</p>";
			}
		}
		else 
		{
			$file_name = $pic_id.".".$file_ext;
			move_uploaded_file($_FILES["file_pic"]["tmp_name"], '../images/'.$file_name);
			$data['pin_id'] = $pin_id;
			$data['pic_id'] = $pic_id;
	    }
	}
}

echo json_encode($data);
?>
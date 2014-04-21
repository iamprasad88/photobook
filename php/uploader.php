<?php
require_once '../views/access.php';
?>

<html>
<head>
	<link href="../css/default.css" rel="stylesheet">
</head>
<body>

<header><h1><a href="kk">aasdflksflkjdlkkkkkkkkkkkkkkkkkkkkkkkk</a></h1></header>

<?php

if(empty($_POST["website"]))
{
//    $_FILES["uploadedfile"]["name"] – the name of the uploaded file
//    $_FILES["uploadedfile"]["type"] – the type of the uploaded file
//    $_FILES["uploadedfile"]["size"] – the size in bytes of the uploaded file
//    $_FILES["uploadedfile"]["tmp_name"] – the name of the temporary copy of the file stored on the server
//    $_FILES["uploadedfile"]["error"] – the error code resulting from the file upload


	$type = $_FILES["uploadedfile"]["type"];
	$location = "../users/".$_SESSION['uname']."/userprofilepic.jpg";
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $location)) 
	{
	//	if(exif_imagetype('../users/Bharath/userprofilepic.jpg'))
		{	
			echo "The file ".basename( $_FILES['uploadedfile']['name'])." has been uploaded <br/>";
			echo '<div class="show-pin2"><img class="show-pin2" src='.$location.' alt="Check image path"></div><br/>';
			echo "<center><a href = 'http://localhost/photobook/php/edit_profile.php'>Click to go back</a></center><br/>";
		}
		//else
			//echo "There was an error uploading the file, only image files can be uploaded. The file you just selected is of the type: $type<br/>";
	} 
	else 
	{
		echo "Please select a file or check to make sure the size of the file does not exceed 8MB";
	}


}
else
{
	function test_input($data)
	{
	     $data = trim($data);
	     $data = stripslashes($data);
	     $data = htmlspecialchars($data);
	     return $data;
	}
	$location = "../users/".$_SESSION['uname']."/userprofilepic.jpg";
	if(empty($_POST["website"]))
		echo "No URL entered";
	else
	{
		$website = test_input($_POST["website"]);
		// check if URL address syntax is valid (this regular expression also allows dashes in the URL)
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website))
		{
			$websiteErr = "Invalid URL";
			echo "<br/>";
			echo $websiteErr;
		}
		elseif(@exif_imagetype($_POST["website"]))
		{
			file_put_contents($location, file_get_contents($_POST["website"]));
			echo "File uploaded<br/>";
			echo '<img style="border:1" class="image-upload" src='.$location.' alt="Check image path"><br/>';
			echo "<center><a href = 'http://localhost/photobook/php/edit_profile.php'>Click to go back</a></center><br/>";
		}
		else echo "<br/>File type not supported";
	}
}



?>
</body>
</html>
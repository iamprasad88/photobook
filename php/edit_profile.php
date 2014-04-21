<?php
require_once '../views/access.php';
include_once '../php/queries.php';
?>

<html>
<head>
	<link href="../css/default.css" rel="stylesheet">
  		<script src="../js/jquery.js"></script>
		<script>
			$(document).ready(function(){
				$("#uuser").submit(function(event){
					event.preventDefault();
					$.post('../php/update_user.php', $(this).serialize(), function(data) {
						if (data.errorcode == 1) {
							$("#errmsg").html("<p class='ui-error'>" + data.errormsg + "</p>").show().fadeOut(7000);
						} else if (data.errorcode == 2) {
							$("#errmsg").html("<p class='ui-info'>" + data.errormsg + "</p>").show().fadeOut(7000);
						} else {
							alert("Profile was successfully updated!!");
							document.location = "/photobook/views/user_profile.php";
						}		
					}, 'json');
//---------------------------------------------------------------------------------------------------------------
				});
				$("#cancel").click(function(){
					document.location = "/photobook/views/user_profile.php";
				});
			});
		</script>
</head>
<body>

<?php

$B = <<<B
<form enctype="multipart/form-data" action="../php/uploader.php" method="post" style="margin:auto;">
Choose a file to upload: 
<input name="uploadedfile" type="file"><br/>
<br/>Or enter the url of the file: 
<input name="website" type="text"><br>
<input value="Upload File" type="submit">
</form>
B;

$result = get_user_details("{$_SESSION['uname']}");
$row = mysql_fetch_array($result);
$location = "../users/".$_SESSION['uname']."/userprofilepic.jpg";


$male_checked = "";
$female_checked = "";
$nd_checked = "";

if($row[4]=="Male")
	$male_checked = 'checked';
if($row[4]=="Female")
	$female_checked = 'checked';
if($row[4]=="Not Declared")
	$nd_checked = 'checked';
$C = <<<C

		<form action="../php/update_user.php" id="uuser" method="post" style="margin:auto;">
		<div class='show-pin'>
		<img class="show-pin" src=$location alt="User Profile Picture">
		</div>
			<table id = "center1">
				<tr><td>User Name</td><td><input type="text" name="uname" value="$row[0]" readonly/></td></tr>
				<tr><td>Password</td><td><input type="password" name="pwd" value="$row[7]"/></td></tr>
				<tr><td>First Name</td><td><input type="text" value="$row[1]" name="fname" /></td></tr>
				<tr><td>Last Name</td><td><input type="text" name="lname" value="$row[2]"/></td></tr>
				<tr><td>Gender</td><td>M <input type="radio" name="gender" value="Male" $male_checked/>&nbsp;F<input type="radio" name="gender" value="Female" $female_checked/><br/>I prefer not to declare<input type="radio" name="gender" value="Not declared" $nd_checked/></td></tr>
				<tr><td>Email</td><td><input type="text" name="email" value="$row[3]"/></td></tr>
				<tr><td>Language</td><td><input type="text" name="language" value="$row[5]"/></td></tr>
				<tr><td>Country</td><td><input type="text" name="country" value="$row[6]"/></td></tr>
				<tr><td><hr></td><td><hr></td></tr>
				<tr><td><input value="Save" type="submit" name="save" /></td><td><input value="Cancel" type="button" name="save" id="cancel" /></td></tr>
				
			</table>
		</form>
		

C;

echo $C;
echo "<br/>";
echo $B;
?>
</body>
</html>
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
				$("#menu_pins").click(function(){
        				$(".toggle_menu").not("#toggle_pins").slideUp(500,function(){
        					$("#toggle_pins").slideToggle();
        				});
        			});

        			$("#menu_boards").click(function(){
        				$(".toggle_menu").not("#toggle_boards").slideUp(500,function(){
        					$("#toggle_boards").slideToggle();
        				});
        			});
        			$(".toggle_menu").hide();
        			
			});
		</script>
</head>
<body>

<header id="header">
	<h1>
		<a>
			<table>
				<td><a class="addbutton menu_button" id="menu_pins">Pins</a>
				<a class="addbutton menu_button" id="menu_boards">Boards</a>
				<a href="/photobook/views/my_streams.php"class="addbutton menu_button" id="menu_streams">Streams</a>
				<a href="/photobook/views/user_profile.php" class="addbutton menu_button" id="menu_user">User Accounts</a>
				<a href="/photobook/views/my_friends.php" class="addbutton menu_button" id="menu_user">My Friends</a></td>
			</table>
		</a>
	</h1>
</header>
<form class="toggle_menu" id="toggle_pins">
	<a href="/photobook/views/my_pins.php" class="addbutton menu_button" id="menu_my_pins">My Pins</a>
	<a href="/photobook/views/all_pins.php" class="addbutton menu_button" id="menu_search_pins">Search Pins</a>
</form>
<form class="toggle_menu" id="toggle_boards">
	<a href="/photobook/views/my_boards.php" class="addbutton menu_button" id="menu_my_boards">My Boards</a>
	<a href="/photobook/views/all_boards.php" class="addbutton menu_button" id="menu_search_boards">Search Boards</a>
</form>
<?php

$B = <<<B
<form enctype="multipart/form-data" action="../php/uploader.php" method="post" id="center1" style="margin:auto;">
Choose a file to upload: 
<input name="uploadedfile" type="file"><br/>
<br/>Or enter the url of the file: 
<input name="website" type="text"><br>
<input value="Upload File" type="submit" class="addbutton">
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
				<tr><td><input value="Save" type="submit" name="save" class="menubutton"/></td><td><input value="Cancel" class="menubutton" type="button" name="save" id="cancel" /></td></tr>
				
			</table>
		</form>
		

C;

echo $C;
echo "<br/>";
echo $B;
?>
</body>
</html>
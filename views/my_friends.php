<?php
require_once 'access.php';
include_once '../php/queries.php';
?>


<html>
<head>
  <link href="../css/default.css" rel="stylesheet">
  		<script src="../js/jquery.js"></script>
		<script>
			$(document).ready(function(){
				$("#invited_message").hide();
				$("#invited_message").submit(function(event){
					event.preventDefault();
					$.post('../php/create_invitation.php', $(this).serialize(), function(data) {
						if (data.errorcode == 1) {
							$("#errmsg").html("<p class='ui-error'>" + data.errormsg + "</p>").show().fadeOut(7000);
						} else if (data.errorcode == 2) {
							$("#errmsg").html("<p class='ui-info'>" + data.errormsg + "</p>").show().fadeOut(7000);

						} else
							$("#invited_message").html("<p class='ui-success'>" + $("#invited_message").find("[name=invited_user]").val() + " has been invited!!</p>").show().fadeOut(3000);
					}, 'json');
//---------------------------------------------------------------------------------------------------------------
				});

 				$("#flip").click(function(){
    				$("#invited_message").slideToggle("slow");
			
    			
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
<title>Photobook - Friend List</title>
<body>

<header id="header">
	<h1>
		<a>
			<table>
				<td><a class="addbutton menu_button" id="menu_pins">Pins</a>
				<a class="addbutton menu_button" id="menu_boards">Boards</a>
				<a href="/photobook/views/my_streams.php"class="addbutton menu_button" id="menu_streams">Streams</a>
				<a href="/photobook/views/user_profile.php" class="addbutton menu_button" id="menu_user">User Accounts</a>
				<a href="/photobook/views/my_friends.php" class="addbutton menu_button" id="menu_user">My Friends</a>
				<a href="/photobook/php/logout.php" class="addbutton menu_button" id="menu_user">Logout</a></td>
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







<h3 class='toggle_add addbutton' id="flip">Click to add new friends!!</h3>
<?php   
if(isset($_POST['invited_user']))
{
	echo $_POST["invited_user"]." has been invited!!";
}
?>


<form action="/" method="post" id="invited_message" style="margin:auto;">
Enter user name:
<input name="invited_user" type="text">
Enter invite message:
<input name="message" type="text">
<input value="Invite" type="submit">
</form>
<!-- </div> -->
<div hidden id="errmsg"></div>

<br/><br/>

<h2>Here are all your friends!!</h2>
<?php
	
	$result1 = get_friends_list();
	while($row1 = mysql_fetch_array($result1))
	{
		echo "<a href = 'http://localhost/photobook/views/user_profile.php?username=$row1[0]'> $row1[1] $row1[2] </a>";

	$result2 = get_user_details($row1[0]);
	$row = mysql_fetch_array($result2);
	$location = "../users/".$row[0]."/userprofilepic.jpg";
$A = <<<A
<form action="/" id="cuser" >
<div class='show-pin'>
<img class="show-pin" src=$location alt="User Profile Picture">
</div>
	<table style="margin:auto;">
		<tr><td><label>User Name</label></td><td>$row[0]</td></tr>
		<tr><td><label>First Name</label></td><td>$row[1]</td></tr>
		<tr><td><label>Last Name</label></td><td>$row[2]</td></tr>
		<tr><td><label>Gender</label></td><td>$row[4]</td></tr>
		<tr><td><label>Email</label></td><td>$row[3]</td></tr>
		<tr><td><label>Language</label></td><td>$row[5]</td></tr>
		<tr><td><label>Country</label></td><td>$row[6]</td></tr>
	</table>
</form>
<br/>		
A;
	echo $A;
	}

?>
</body>
</html>
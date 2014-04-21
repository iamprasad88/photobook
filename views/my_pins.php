<?php
require_once 'access.php';
$user_id = isset($_SESSION['uname'])?$_SESSION['uname']:"";
?>
<html>
	<head>
		<title>Photobook - My Pins</title>
		<link href="/photobook/css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery.js"></script>
		<script src="/photobook/js/jquery.form.js"></script>
		<script src="/photobook/js/custom.js"></script>
		<script>
		var user_id = "<?php echo $user_id; ?>";
			$(document).ready(function() {
				var $pins = $("#pins");

				$("#file_box").show();
				$("#url_pic_box").hide();
				$("#url_site_box").hide();

				$("#file_upload").click(function(){
					$("#file_box").show();
					$("#url_pic_box").hide();
					$("#url_site_box").hide();
				});

				$("#url_upload").click(function(){
					$("#file_box").hide();
					$("#url_pic_box").show();
					$("#url_site_box").show();
				});

				loadPins($pins,{view_mode:"my"},user_id);

				$("#add_pin").hide();
				$(".toggle_add").click(function(){
					$("#add_pin").slideToggle("slow");
					document.getElementsByName("name")[0].focus();
				});

				$.post("../php/show_boards.php", {
					view_mode:"my"
				}, function(data) {
					var $boards = $("#boards");
					for (var i = 0; i < data.length; i++) {
						var board = jQuery.parseJSON(data[i]);
						$boards.append("<option value='" + board.board_id + "'>" + board.name + "</option>");
					}
				}, 'json');

				$('#add_pin').ajaxForm(function(data) { 
					data = jQuery.parseJSON(data);
					if (data.errorcode == 1) {
						$("#errmsg").html(data.errormsg).show().fadeOut(5000);
					} else {
						loadPins($pins,{view_mode:"my"},user_id);
						$("#errmsg").html(data.errormsg+"<p class='ui-success'>Pin added successfully</p>").show().fadeOut(5000);
						$("#add_pin").slideToggle("slow");
					}
            	},'json');
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

				// $("#view_pin").hide();
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
</form>		<!-- <h3 class="toggle_add" style="text-align:left;">Add Pin</h3> -->
		<table stlyle="width=100%">
			<tr>
				<td><button class="toggle_add togglebar menubutton" style="text-align:left;">Add Pins</button></td>
			</tr>
		</table>
			<form id="add_pin" method="post" action="/photobook/php/createpin.php" enctype="multipart/form-data">
				<table>
					<tr><td><label>Title</label></td><td><input type="text" name="title"/></td></tr>
					<tr><td style="vertical-align:middle;"><label>Description</label></td><td><textarea name="description"></textarea></td></tr>
					<tr><td><label>Pinboard</label></td><td><select name='pinboard_id' form='add_pin' id="boards"/></td></tr>
					<tr><td>Upload Method</td><td><label>By URL</label><input type="radio" name="upload_method" id="url_upload" value="url"><label>By File</label><input type="radio" name="upload_method" id="file_upload" value="file" checked="true"></td></tr>
					<tr id="file_box"><td><input type="file" name="file_pic"/></td></tr>
					<tr id="url_pic_box"><td><label>Picture URL</label></td><td><input type="text" name="url_pic"/></td></tr>
					<tr id="url_site_box"><td><label>Site URL</label></td><td><input type="text" name="url_site"/></td></tr>
					<tr><td><label>Tags</label></td><td><input type="text" name="tags"/></td></tr>
					<tr><td><input type="submit" class="addbutton" value="Add Pin"/></td></tr>
				</table>
				
			</form>

		<div id="pins"></div>
		<div id="temp" hidden></div>
		<div id="errmsg"></div>
	</body>
</html>
<?php
require_once 'access.php';
$bid = isset($_POST['board_id'])?$_POST['board_id']:1;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Photobook - Board Pins</title>
		<link href="../css/default.css" rel="stylesheet" />
		<script src="../js/jquery-1.8.3.js"></script>
		<script>
		var board_id = <?php echo $bid;?>;
			function makepin(title, filename, tags) {
				pintext = "<div class='show-pin ui-corner-all'>";
				pintext += "<lable>" + title + "</lable>";
				pintext += "<br />";
				pintext += "<img src='" + filename + "' alt='" + filename + "' class='show-pin'>";
				//pintext += "<p>"+tags+"</p>";
				pintext += "</div>";
				return pintext;
			}


			$(document).ready(function() {
				var $pins = $("#pins");
				$.post("../php/show_pins.php", {
					view_mode : "board",
					board_id : board_id
				}, function(data) {
					for (var i = 0; i < data.length; i++) {

						var pin = jQuery.parseJSON(data[i]);
						$pins.prepend(makepin(pin.title, "/photobook/images/"+pin.fpath, pin.tags));
					}
				}, 'json');
			});
		</script>
	</head>
	<body>
		<header><h1><a href="kk">aasdflksflkjdlkkkkkkkkkkkkkkkkkkkkkkkk</a></h1></header>
		<h3 class="toggle_add" style="text-align:left;">Add Pin</h3>
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
		
		<div id="errmsg"></div>
		<div id="pins"></div>
	</body>
</html>
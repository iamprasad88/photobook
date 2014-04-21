<?php
require_once 'access.php';
?>
<html>
	<head>
		<title>Photobook - Add Pins</title>
		<link href="/photobook/css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script>
		<script>
			$(document).ready(function(){
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

				$.post("../php/show_boards.php", {
					view_mode:"my"
				}, function(data) {
					var $boards = $("#boards");
					for (var i = 0; i < data.length; i++) {
						var board = jQuery.parseJSON(data[i]);
						$boards.append("<option value='" + board.board_id + "'>" + board.name + "</option>");
					}
				}, 'json');

				$('#addpins').ajaxForm(function(data) { 
					data = jQuery.parseJSON(data);
					if (data.errorcode == 1) {
						$("#errmsg").html("<p>" + data.errormsg + "</p>").show();
					} else {
						window.location.replace('/photobook/views/my_pins.php');
					}
            },'json');

			});
		</script>
	</head>
	<body>
		<header><h1><a href="kk">aasdflksflkjdlkkkkkkkkkkkkkkkkkkkkkkkk</a></h1></header>
			<form id="addpins" method="post" action="/photobook/php/createpin.php" enctype="multipart/form-data">
				<table>
					<tr><td><label>Title</label></td><td><input type="text" name="title"/></td></tr>
					<tr><td><label>Description</label></td><td><textarea name="description"></textarea></td></tr>
					<tr><td><label>Pinboard</label></td><td><select name='pinboard_id' form='addpins' id="boards"/></td></tr>
					<tr><td>Upload Method</td><td><label>By URL</label><input type="radio" name="upload_method" id="url_upload" value="url"><label>By File</label><input type="radio" name="upload_method" id="file_upload" value="file" checked="true"></td></tr>
					<tr id="file_box"><td><input type="file" name="file_pic"/></td></tr>
					<tr id="url_pic_box"><td><label>Picture URL</label></td><td><input type="text" name="url_pic"/></td></tr>
					<tr id="url_site_box"><td><label>Site URL</label></td><td><input type="text" name="url_site"/></td></tr>
					<tr><td><label>Tags</label></td><td><input type="text" name="tags"/></td></tr>
					<tr><td><input type="submit"/></td></tr>
				</table>
			</form>
		<div id="pins"></div>
		<div hidden id="errmsg" />
	</body>
</html>
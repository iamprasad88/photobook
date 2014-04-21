<?php
require_once 'access.php';
?>
<html>
	<head>
		<title>Photobook - My Pins</title>
		<link href="../css/default.css" rel="stylesheet" />
		<script src="../js/jquery-1.8.3.js"></script>
		<script>
		function makepin (title,filename,tags){
			pintext = "<div class='ui-widget-content show-pin ui-corner-all'>";
			pintext += "<lable>"+title+"</lable>";
			pintext += "<br /><br />";
			pintext += "<img class = 'show-pin' src='/photobook/images/"+filename+"' alt='"+filename+"'>";
			//pintext += "<p>"+tags+"</p>";
			pintext += "</div>";
			return pintext;
		}
			$(document).ready(function() {
				var $pins = $("#pins");
				$.post("../php/show_pins.php", {view_mode:"my"}, function(data) {
					for (var i = 0; i < data.length; i++) {
						var pin = jQuery.parseJSON(data[i])
						$pins.prepend(makepin(pin.title,pin.fpath,pin.tags));
					}
				},'json');
			});
		</script>
	</head>
	<body>
		<header><h1><a href="kk">aasdflksflkjdlkkkkkkkkkkkkkkkkkkkkkkkk</a></h1></header>
		<div id="pins"></div>
	</body>
</html>
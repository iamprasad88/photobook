<?php
require_once 'access.php';
$uname = isset($_POST['uname'])?$_POST['uname']:"";
?>
<html>
	<head>
		<title>Photobook - My Pins</title>
		<link href="../css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery.js"></script>
		<script src="/photobook/js/custom.js"></script>
		<script>
		var uname = "<?php echo $uname; ?>";
		// function makepin (title,filename,tags){
		// 	pintext = "<div class='ui-widget-content show-pin ui-corner-all'>";
		// 	pintext += "<lable>"+title+"</lable>";
		// 	pintext += "<br /><br />";
		// 	pintext += "<img class = 'show-pin' src='/photobook/images/"+filename+"' alt='"+filename+"'>";
		// 	//pintext += "<p>"+tags+"</p>";
		// 	pintext += "</div>";
		// 	return pintext;
		// }
			$(document).ready(function() {
				// var $pins = $("#pins");
				// $.post("../php/show_pins.php", {view_mode:"user",uname:uname}, function(data) {
				// 	for (var i = 0; i < data.length; i++) {
				// 		var pin = jQuery.parseJSON(data[i])
				// 		$pins.prepend(makepin(pin.title,pin.fpath,pin.tags));
				// 	}
				// },'json');
		var $pins = $("#pins");
		loadPins($pins,{view_mode:"user", uname:uname});

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
</form>		<div id="pins"></div>
		<div id="temp" hidden />
	</body>
</html>
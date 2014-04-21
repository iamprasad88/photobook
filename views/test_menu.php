<?php
require_once 'access.php';
$stream_id = key_exists('stream_id', $_POST) ? $_POST['stream_id'] : "";
$stream_name = key_exists('stream_name', $_POST) ? $_POST['stream_name'] : "";
?>
<!DOCTYPE html>
<html>
	<head>
		<link href="../css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery.js"></script>
		<script src="/photobook/js/custom.js"></script>
		<script src="/photobook/js/zino.tooltip.min.js"></script>
		<script src="/photobook/js/zino.menu.min.js"></script>
		<link rel="stylesheet" href="/photobook/css/zino.core.css">
        <link rel="stylesheet" href="/photobook/css/zino.tooltip.css">
	    <link rel="stylesheet" href="/photobook/css/zino.menu.css">
        <script type="text/javascript">
        	$(document).ready(function(){
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

        			// $("#menu_streams").click(function(){
        				
        			// });

        			// $("#menu_user").click(function(){
        				
        			// });
        			$(".toggle_menu").hide();

        			// $("#menu_my_pins").click(function(event){
        			// 	document.location.replace('http://www.google.com');
        			// });
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
    </body>
</html>
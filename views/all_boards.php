<?php
require_once 'access.php';
$uname = isset($_SESSION['uname'])?$_SESSION['uname']:"";
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Photobook - Boards</title>
		<link href="../css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery.js"></script>
		<script src="/photobook/js/custom.js"></script>
		<script src="/photobook/js/zino.tooltip.min.js"></script>
		<link rel="stylesheet" href="/photobook/css/zino.core.css">
        <link rel="stylesheet" href="/photobook/css/zino.tooltip.css">
		<script src="/photobook/js/zino.tooltip.min.js"></script>
		<script src="/photobook/js/custom.js"></script>
		<script>
			var uname = "<?php echo strtolower($uname);?>";

			$(document).ready(function() {

				var $boards = $("#boards-list");
				var $boards_desc = $("#boards-desc");
				



				$("#to_submit").hide();

				// loadBoards($boards,$boards_desc,{view_mode:"all"},null);



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
				$("#searchboards").submit(function(event){
					event.preventDefault();
					// event.stopPropagation();
					var data = $(this).serializeObject();
					// alert(data.boardkw);
					loadBoards($boards,$boards_desc,{view_mode:"search",boardkw:data.searchkw},null);

				});

				$("#searchkw").keyup(function(){
					$("#searchboards").submit();
				});

			});
		</script>
	</head>
	<body>
		<header id="header">
	<h1>
		<a>
			<table >
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

		<table border=1 style="width:100%;margin:auto;">
			<tr><td style="width:100%;"><form style="width:90%;" name="searchboards" id="searchboards"><input type="text" id="searchkw" name="searchkw"></input><input type="submit" value="Search" form="searchboards"></input></form></td></tr>
			<tr>
				<td style="width:100%">
					<section class='clear'>
						<nav style="width:100%">
							<h3 >All Boards</h3>
							<ul style="width: 100%">
							<div id="boards-list"></div>
							</ul>
						</nav>
					</section>
				</td>
				<!-- <td style="vertical-align:top;padding:150px;"><div id="show_desc" /></td> -->
			</tr>
		</table>
		<!-- <div hidden="hidden" id="boards-desc"></div> -->
		<form hidden="hidden" id="to_submit" action='/photobook/views/board_pins.php' method='post'>
			<input type='hidden' name='board_id' />
			<input type='hidden' name='board_name' />
			<input type='hidden' name='board_user' />
		</form>
	</body>
</html>
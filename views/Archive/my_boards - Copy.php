<?php
require_once 'access.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Photobook - Boards</title>
		<link href="../css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery-1.8.3.js"></script>
		<script src="/photobook/js/jquery-ui-1.9.2.custom.js"></script>
		<script>

			$(document).ready(function() {

				var $boards = $("#boards-list");
				var $boards_desc = $("#boards-desc");

				$("#to_submit").hide();

				$.post("../php/show_boards.php", {
					view_mode:"my"
				}, function(data) {
					for (var i = 0; i < data.length; i++) {
						var board = jQuery.parseJSON(data[i]);
						$boards.append("<li id='" + board.board_id + "' class='board'><a>" + board.name + "</a></li>");
						$boards_desc.append("<div id='"+board.board_id+"_desc'>"+board.description+"</div>");
					}

					$(".board").mouseover(function() {
						$("#show_desc").html($("#"+$(this).attr("id")+"_desc").html());
					});
					$(".board").mouseout(function() {
						$("#show_desc").html("");
					});
					$(".board").click(function() {
						$("#to_submit").find("input").val($(this).attr("id"));
						$("#to_submit").submit();
					});
				}, 'json');
			});
		</script>
	</head>
	<body>
		<header><h1><a href="kk">aasdflksflkjdlkkkkkkkkkkkkkkkkkkkkkkkk</a></h1></header>
		<table border=1>
			<tr>
				<td style="width:95%">
					<section class='clear'>
						<nav style="width:100%">
							<h3 >My Boards</h3>
							<ul style="width: 100%">
							<div id="boards-list"></div>
							</ul>
						</nav>
					</section>
				</td>
				<td style="vertical-align:top;padding:150px;"><div id="show_desc" /></td>
			</tr>
		</table>
		<div hidden="hidden" id="boards-desc"></div>
		<form hidden="hidden" id="to_submit" action='/photobook/views/board_pins.php' method='post'><input type='hidden' name='board_id' /></form>
	</body>
</html>
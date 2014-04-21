<?php
require_once 'access.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Photobook - streams</title>
		<link href="../css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery-1.8.3.js"></script>
		<script src="/photobook/js/jquery-ui-1.9.2.custom.js"></script>
		<script>

		var uname = "<?php echo $_POST['uname']; ?>";
			$(document).ready(function() {

				var $streams = $("#streams-list");
				var $streams_desc = $("#streams-desc");
				$("#stream_title").prepend(uname);

				$("#to_submit").hide();

				$.post("../php/show_streams.php",  {
					view_mode:"user",
					uname:uname
				}, function(data) {
					for (var i = 0; i < data.length; i++) {
						var stream = jQuery.parseJSON(data[i]);
						$streams.append("<li id='" + stream.stream_id + "' class='stream'><a>" + stream.name + "</a></li>");
						$streams_desc.append("<div id='"+stream.stream_id+"_desc'>"+stream.description+"</div>");
					}

					$(".stream").mouseover(function() {
						$("#show_desc").html($("#"+$(this).attr("id")+"_desc").html());
					});
					$(".stream").mouseout(function() {
						$("#show_desc").html("");
					});
					$(".stream").click(function() {
						$("#to_submit").find("input").val($(this).attr("id"));
						$("#to_submit").submit();
					});
				}, 'json');
			});
		</script>
	</head>
	<body>
		<header><h1><a href="kk">aasdflksflkjdlkkkkkkkkkkkkkkkkkkkkkkkk</a></h1></header>
		<table>
			<tr>
				<td style="width:80%">
					<section class='clear'>
						<nav style="width:100%">
							<h3 id="stream_title">'s' Streams</h3>
							<ul style="width: 100%">
							<div id="streams-list"></div>
							</ul>
						</nav>
					</section>
				</td>
				<td style="vertical-align:top;padding:50px"><div id="show_desc" /></td>
			</tr>
		</table>
		<div hidden="hidden" id="streams-desc"></div>
		<form hidden="hidden" id="to_submit" action='/photobook/views/stream_pins.php' method='post'><input type='hidden' name='stream_id' /></form>
	</body>
</html>
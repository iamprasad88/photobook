$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function makepin (pin,user_id){
	var likes = 0;
	if(pin.pin_id==pin.root_pin_id){
		likes = pin.tlikes;
	}
	else{
		likes = pin.llikes;
	}
	pintext = "";
	pintext += "<div class='show-pin' id='"+pin.pin_id+"'>";
	pintext += "<table>";
	pintext += "	<tr><td><h3 id='"+pin.pin_id+"pin_title' style='text-align:center;' >"+pin.title+"</h3></td>";
	pintext += "<td><div><button class='togglelike' id='"+pin.pin_id+"togglelike' value=''/></div></td><td>";
	if(pin.uname==user_id){
		pintext += "<div><button class='unpin' style='text-align:right;' id='"+pin.pin_id+"unpin' value=''/></div>";
	}
	pintext += "</td></tr>";
	pintext += "	<tr><td  colspan='4' style='text-align:center;'><a><img id='"+pin.pin_id+"pin_pic' src='/photobook/images/"+pin.fpath+"' /></a></td></tr>";
	pintext += "	<tr><td style='width:25%'><b>Description: </b></td>";
	pintext += "	<td style='text-align:left;'><p id='"+pin.pin_id+"pin_desc'>"+pin.description+"</p></td></tr>";
	pintext += "	<tr><td><b>Tags: </b></td>";
	pintext += "	<td style='text-align:left;'><p id='"+pin.pin_id+"pin_tags' >"+pin.tags+"</p></td></tr>";
	pintext += "	<tr><td><b>Likes</b></td><td style='text-align:left;'><p id='"+pin.pin_id+"likes' >"+likes+"</p></td></tr>";
	pintext += "</table>";
	pintext += "	<tr><td><div hidden id='"+pin.pin_id+"root' >"+pin.root_pin_id+"</div></td></tr>";
	pintext += "	<tr><td><div hidden id='"+pin.pin_id+"pic' >"+pin.pic_id+"</div></td></tr>";
	pintext += "</div>";
	updateLikeButton(pin.pin_id,pin.pin_id+"togglelike");

	return pintext;
}


function refreshPin(pin_id){
	$.post("../php/show_pins.php",{
		view_mode:"pin",
		pin_id:pin_id
		},
		function(data){
			data = jQuery.parseJSON(data[0]);
			$("#"+pin_id+"pin_title").html(data.title);
			$("#"+pin_id+"pin_tags").html(data.tags);

		var likes = 0;
		if(data.pin_id==data.root_pin_id){
			likes = data.tlikes;
		}
		else{
			likes = data.llikes;
		}

		updateLikeButton(pin_id,pin_id+"togglelike");

			$("#"+pin_id+"likes").html(likes);
		},'json');
}

function loadPins($pins,post_vars,user_id){
	$pins.html("");
	var hover_pin_id = "";
	var hover_pic_id = "";
		$.post("../php/show_pins.php", post_vars, function(data) {
			for (var i = 0; i < data.length; i++) {
				var pin = jQuery.parseJSON(data[i])
				$pins.append(makepin(pin,user_id));
			}
		$(".show-pin").click(function(){

			$("#temp").html("<form id='view_pin' action='/photobook/views/view_pin.php' method='post' hidden> \
								<input type='text' name='pin_id'> \
							</form>");
			$("#view_pin").find("input").val($(this).attr("id"));
			$("#view_pin").submit();
		});
		$(".show-pin").hover(function(){
			hover_pin_id = $(this).attr("id");
			hover_pic_id = $("#"+hover_pin_id+"pic").html();
			hover_root_pin_id = $("#"+hover_pin_id+"root").html();
		});
		$(".togglelike").click(function(event){
			event.stopPropagation();
			$.post("../php/toggle_like.php", {
				pin_id:hover_pin_id,
				pic_id:hover_pic_id,
				root_pin_id:hover_root_pin_id
			}, function(data) {
					refreshPin(hover_pin_id);
						}, 'json');
		});
		$(".unpin").click(function(event){
			event.stopPropagation();
			var pin_id = hover_pin_id;
			$.post("../php/unpin.php", {
				pin_id:pin_id
			}, function(data) {
					if(data.errorcode==0){
						$("#"+data.pin_id).fadeOut(3000,function(){
							$(this).remove();
						});
						$("#errmsg,#err_msg").html("<p class='ui-success'>Successfully Unpinned</p>").show().fadeOut(8000);
					}
					else{
						$("#errmsg,#err_msg").html(data.errormsg).show().fadeOut(8000);
					}
				}, 'json');

		});
		},'json');

}

function loadBoards($boards,$boards_desc,post_vars,$delete_boards) {

	$(".board").remove();
	$.post("../php/show_boards.php", post_vars, function(data) {
		if($delete_boards!=null){
			$delete_boards.html("");
		}
		var temp = "";
		for (var i = 0; i < data.length; i++) {
			var board = jQuery.parseJSON(data[i]);
			$boards.append("<li title='<b>Description:</b><br/>"+board.description
				+"' id='" + board.board_id + 
				"' class='board'><a class='board_link'>" + board.name + "</a></li>");
			$boards.append("<div id='"+board.board_id+"_name' hidden>"+board.name+"</div>");
			$boards.append("<div id='"+board.board_id+"_desc' hidden>"+board.description+"</div>");
			$boards.append("<div id='"+board.board_id+"_cmnt_status' hidden>"+board.cmnt_status+"</div>");
			$boards.append("<div id='"+board.board_id+"_uname' hidden>"+board.uname+"</div>");
			temp += "<option value='"+board.board_id+"'>"+board.name+"</option>";
		}

			$("#delete_board_name").html(temp);
		$(".board").mouseover(function() {
			$("#show_desc").html($("#"+$(this).attr("id")+"_desc").html());
		});
		$(".board").mouseout(function() {
			$("#show_desc").html("");
		});
		$(".board").click(function() {
			var board_id = $(this).attr("id");
			var board_name = $("#"+board_id+"_name").html();
			var board_user = $("#"+board_id+"_uname").html();
			$("#to_submit").find("input[name=board_id]").val(board_id);
			$("#to_submit").find("input[name=board_name]").val(board_name);
			$("#to_submit").find("input[name=board_user]").val(board_user);
			$("#to_submit").submit();
		});
		if($delete_boards!=null){
			$delete_boards.change(function(){
				$("#delete_board").find("textarea").html($("#"+$(this).val()+"_desc").html());
				$("#delete_board").find("#delete_cmnt_status").val($("#"+$(this).val()+"_cmnt_status").html());
			});
		}

		$(".board").zinoTooltip({
    	follow: true,
		});
	}, 'json');
}

function viewPin(pin_id,user_id){
// fetch pin details from pin id
// refresh pin
$.post("../php/show_pins.php", {
	view_mode:"pin",
	pin_id:pin_id
	}, function(data) {
			var pin = jQuery.parseJSON(data[0]);
			$("#pin_title").html(pin.title);
			$("#pin_pic").attr("src","/photobook/images/"+pin.fpath);


			$("#pin_desc").html(pin.description);
			$("#pin_tags").html(pin.tags);
			if(pin.pic_url!="")
				$("#pin_pic_url").attr("href",pin.pic_url).show();
			else
				$("#pin_pic_url").hide();

			if(pin.site_url!="")
				$("#pin_site_url").attr("href",pin.site_url).show();
			else
				$("#pin_site_url").hide();
			if(pin.pin_id==pin.root_pin_id){
				$("#llikes").html(pin.tlikes);
			} else {
			$("#llikes").html(pin.llikes);
			}
			$("#cmnt_status").html(pin.cmnt_status);

			// if(pin.cmnt_status=="Private"){
			// 	$("post_comments").hide();
			// }

			$("#likes_pin_id").val(pin.pin_id);
			$("#likes_pic_id").val(pin.pic_id);
			$("#likes_root_pin_id").val(pin.root_pin_id);
			$("#likes_user_id").val(pin.uname);

			if(pin.uname!=user_id){
					$(".toggle_unpin").hide();
					$("#unpin").hide();
			}
		}, 'json');

	updateLikeButton(pin_id,"like_button");
}


function updateLikeButton (pin_id,button_id){
	$.post("../php/check_like.php", {
	pin_id:pin_id
	}, function(data) {
			if(data.errorcode==0){
				if(data.liked==true){
					$("#"+button_id).removeClass("unlikedButton");
					$("#"+button_id).addClass("likedButton");
				}
				else{
					$("#"+button_id).removeClass("likedButton");
					$("#"+button_id).addClass("unlikedButton");
				}
			}
	}, 'json');
}

function loadComments($comment_list){
				// fetch comments with pin id
				// refresh comments
	$.post("../php/show_comments.php", {view_mode:"pin", pin_id:pin_id}
		, function(data) {
		
		$comment_list.html("");
		for (var i = 0; i < data.length; i++) {
			var comment = jQuery.parseJSON(data[i]);
			$comment_list.append("<tr><td><p class='pin-comment'>" + comment.message+"</p></td><td>: Posted on "+comment.created+"</td></tr>");
		}
	}, 'json');
}


function loadStreams ($streams,$streams_desc,$delete_stream) {
	$(".stream").remove();
	$.post("../php/show_streams.php",  {
		view_mode:"my"
	}, function(data) {
		$delete_stream.html("");
		for (var i = 0; i < data.length; i++) {
			var stream = jQuery.parseJSON(data[i]);
			$streams.append("<li id='" + stream.stream_id + "' class='stream'><a title = '<b>Description:</b><br/>"+stream.description+"'>" + stream.name + "</a></li>");
			$streams_desc.append("<div id='"+stream.stream_id+"_name' hidden>"+stream.name+"</div>");
			$streams_desc.append("<div id='"+stream.stream_id+"_desc' hidden>"+stream.description+"</div>");
			$streams_desc.append("<div id='"+stream.stream_id+"_keyword' hidden>"+stream.kquery+"</div>");
			$delete_stream.append("<option value='"+stream.stream_id+"'>"+stream.name+"</option>");
		}
		$(".stream").zinoTooltip({follow: true});
/*			$(".stream").mouseover(function() {
			$("#show_desc").html($("#"+$(this).attr("id")+"_desc").html());
		});*/
		$(".stream").mouseout(function() {
			$("#show_desc").html("");
		});
		$(".stream").click(function() {
			var ID = $(this).attr("id");
			$("#to_submit").find("input[name=stream_id]").val(ID);
			$("#to_submit").find("input[name=stream_name]").val($("#"+ID+"_name").html());
			$("#to_submit").submit();
		});
		$delete_stream.change(function(){
		$("#delete_stream").find("textarea").html($("#"+$(this).val()+"_desc").html());
		$("#delete_stream").find("input[type=text]").val($("#"+$(this).val()+"_keyword").html());
	});
	}, 'json')
}
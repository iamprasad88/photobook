<?php
session_start();
include_once 'queries.php';
/*Should check if pins should be shown for a user or for a board or for a stream or all
 * Should check sort criteria*/
$view_mode = isset($_POST['view_mode']) ? $_POST['view_mode'] : "all";
$conditions = array();
$sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : "";

if ($view_mode == "my") {
	$uname = $_SESSION['uname'];
	array_push($conditions, array("column" => "`pinboards`.user_id", "operation" => "=", "value" => "'$uname'"));
}
if ($view_mode == "user") {
	$uname = $_POST['uname'];
	array_push($conditions, array("column" => "`pinboards`.user_id", "operation" => "=", "value" => "'$uname'"));
}
if ($view_mode == "board") {
	$board_id = $_POST['board_id'];
	array_push($conditions, array("column" => "row_id", "operation" => "=", "value" => "'$board_id'"));
}
if ($view_mode == "stream") {
	$stream_id = $_POST['stream_id'];
####################################################################################################################
	$con = dbConnect();
	$sql = <<<SQL
SELECT 
  board_name name,
  description description,
  comment_status cmnt_status,
  user_id uname,
  pinboards.row_id board_id 
FROM
  pinboards,follow_pinboard
WHERE pinboards.row_id = follow_pinboard.pinboard_id
AND follow_pinboard.stream_id = $stream_id
SQL;

	$result = mysqli_query($con, $sql);
	if ($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
		$boards = $row;
	}
	
	dbClose($con);
#######################################################################################################################

	
	array_push($conditions, array("column" => "stream_id", "operation" => "=", "value" => "'$stream_id'"));
}

if ($view_mode == "search") {
	$boardkw = $_POST['boardkw'];
	array_push($conditions, array("column" => "board_name", "operation" => "LIKE", "value" => "'%$boardkw%'"));
}

if ($view_mode != "stream"){
	get_boards($boards, $conditions, $sort_by);
}
$boardlist = array();
for ($i = 0; $i < count($boards); $i++) {
	$boardlist[$i] = json_encode($boards[$i]);
}
echo json_encode($boardlist);

?>
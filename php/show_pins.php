<?php
session_start();
include_once 'queries.php';
/*Should check if pins should be shown for a user or for a board or for a stream or all
 * Should check sort criteria*/
$view_mode = isset($_POST['view_mode']) ? $_POST['view_mode'] : "all";
$conditions = array();
$sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : "";

if ($view_mode == "my") {
	$uname = addslashes($_SESSION['uname']);
	array_push($conditions, array("column" => "`pins`.user_id", "operation" => "=", "value" => "'$uname'"));
}
if ($view_mode == "user") {
	$uname = addslashes($_POST['uname']);
	array_push($conditions, array("column" => "`pins`.user_id", "operation" => "=", "value" => "'$uname'"));
}
if ($view_mode == "board") {
	$board = addslashes($_POST['board_id']);
	array_push($conditions, array("column" => "`pins`.pinboard_id", "operation" => "=", "value" => "'$board'"));
}
if ($view_mode == "stream") {
	$stream = addslashes($_POST['stream_id']);
	array_push($conditions, array("column" => "stream_id", "operation" => "=", "value" => "'$stream'"));
}
if ($view_mode == "pin") {
	$pin = addslashes($_POST['pin_id']);
	array_push($conditions, array("column" => "`pins`.row_id", "operation" => "=", "value" => "'$pin'"));
}
if ($view_mode == "search") {
	$searchkw = $_POST['searchkw'];
	array_push($conditions, array("column" => "(`pictures`.title", "operation" => "LIKE", "value" => "'%$searchkw%' OR `pins`.tags LIKE '%$searchkw%')"));
}

get_pins($pins, $conditions, $sort_by);

$pinlist = array();
for ($i = 0; $i < count($pins); $i++) {
	$pinlist[$i] = json_encode($pins[$i]);
}
echo json_encode($pinlist);
?>
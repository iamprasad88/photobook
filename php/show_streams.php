<?php
session_start();
include_once 'queries.php';
/*Should check if pins should be shown for a user or for a stream or for a stream or all
 * Should check sort criteria*/
$view_mode = isset($_POST['view_mode']) ? $_POST['view_mode'] : "all";
$conditions = array();
$sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : "";

if ($view_mode == "my") {
	$uname = $_SESSION['uname'];
	array_push($conditions, array("column" => "`streams`.user_id", "operation" => "=", "value" => "'$uname'"));
}
if ($view_mode == "user") {
	$uname = $_POST['uname'];
	array_push($conditions, array("column" => "`streams`.user_id", "operation" => "=", "value" => "'$uname'"));
}

/*
 if ($view_mode == "board") {
 $board_id = $_POST['board_id'];
 array_push($conditions, array("column" => "board_id", "operation" => "=", "value" => "'$board_id'"));
 }*/

if ($view_mode == "stream") {
	$stream_id = $_POST['stream_id'];
	array_push($conditions, array("column" => "stream_id", "operation" => "=", "value" => "'$stream_id'"));
}

get_streams($streams, $conditions, $sort_by);

$streamlist = array();
for ($i = 0; $i < count($streams); $i++) {
	$streamlist[$i] = json_encode($streams[$i]);
}
echo json_encode($streamlist);
?>
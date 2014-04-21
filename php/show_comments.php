<?php
session_start();
include_once 'queries.php';
/*Should check if pins should be shown for a user or for a board or for a stream or all
 * Should check sort criteria*/
$view_mode = isset($_POST['view_mode']) ? $_POST['view_mode'] : "all";
$view_mode = isset($_POST['pin_id']) ? $_POST['pin_id'] : -1;
$conditions = array();
$sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : "";

get_comments(9,$comments);

$commentlist = array();
for ($i = 0; $i < count($comments); $i++) {
	$commentlist[$i] = json_encode($comments[$i]);
}
echo json_encode($commentlist);
?>
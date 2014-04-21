<?php

/**
 * All queries used in the Project
 * 
 * function validate_user($uname, $pwd)
 * create_user($uname, $pwd, $fname, $lname, $email, $gender, &$err_msg)
 * get_pins(&$pins, $conditions, $sort_by)
 * get_boards(&$boards, $conditions, $sort_by)
 * get_streams(&$streams, $conditions, $sort_by)
 * create_pin($pic_path,$user_id,$tags,$url_pic,$url_site,$title,$description,$pinboard_id,$file_ext,&$pic_id,&$pin_id,&$err_msg)
 * create_board($user_id,$name,$description,$comment_privacy,&$board_id,&$err_msg)
 * create_repin($root_pin_id,$user_id,$tags,$title,$pinboard_id,&$pic_id,&$pin_id,&$err_msg)
 */

require_once 'db.php';

function validate_user($uname, $pwd) {

	$return_val = FALSE;
	$sql = "Select user_id from user where user_id='$uname' and password='$pwd'";
	$con = dbConnect();

	if ($result = mysqli_query($con, $sql)) {
		if ($row = mysqli_fetch_row($result)) {
			$return_val = TRUE;
		}
		mysqli_free_result($result);
	}

	dbClose($con);
	return $return_val;
}

function create_user($uname, $pwd, $fname, $lname, $email, $gender, &$err_msg) {
	$con = dbConnect();

	if (!$con) {
		$err_msg = "Unable to connect to Database";
		return FALSE;
	}
	$result = FALSE;
	if (mysqli_num_rows(mysqli_query($con, "SELECT USER_ID FROM USER WHERE USER_ID='$uname'")) > 0) {
		$err_msg = "User Name already Exists";
		return FALSE;
	}

	if (!mysqli_query($con, "INSERT INTO USER(USER_ID, FNAME, LNAME, EMAIL, GENDER, PASSWORD, STATUS) VALUES('$uname','$fname', '$lname', '$email', '$gender', '$pwd','Y')")) {
		$err_msg = mysqli_error($con);
		$result = FALSE;
	} else if (mysqli_affected_rows($con) == 1) {
		$result = TRUE;
	}

	dbClose($con);
	return $result;
}

function get_pins(&$pins, $conditions, $sort_by) {
	$con = dbConnect();
	$sql = <<<SQL
SELECT 
  pictures.title title,
  pictures.description description,
  pictures.total_likes tlikes,
  pins.local_likes llikes,
  pictures.file_name fpath,
  pins.created created,
  pins.tags tags,
  pins.parent_row_id root_pin_id,
  pins.row_id pin_id,
  pictures.row_id pic_id,
  pictures.url_pic pic_url,
  pictures.url_site site_url,
  pins.user_id uname,
  pinboards.comment_status cmnt_status
FROM
  pins,
  pictures,
  pinboards 
WHERE pictures.row_id = pins.picture_id
AND pinboards.row_id = pins.pinboard_id
SQL;

	for ($i = 0; $i < count($conditions); $i++) {
		$condition = $conditions[$i];
		$sql .= " AND " . $condition['column'] . " " . $condition['operation'] . " " . $condition['value'];
	}

	if ($sort_by != "") {
		$sql .= " SORT BY " . $sort_by;
	}

	$result = mysqli_query($con, $sql);
	if ($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
		$pins = $row;
	}

	dbClose($con);
}

function get_if_liked($user_id,$pin_id,&$liked){
	$con = dbConnect();
	$sql_count_likes = mysqli_prepare($con, 'SELECT count(*) count FROM likes WHERE `user_id`= ? AND `pin_id`= ?');
	if(!$sql_count_likes){
		$err_msg .= "Cannot prepare statement to count like".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_count_likes,'si',$user_id,$pin_id)){
		$err_msg .= "Cannot bind statement to count likec".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_count_likes)){
		$err_msg .= "Cannot execute statement to count like".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_bind_result($sql_count_likes,$count)){
		$err_msg .= "Cannot execute statement get count like".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}

	mysqli_stmt_fetch($sql_count_likes);
	mysqli_stmt_close($sql_count_likes);
	if($count>0){
		$liked = TRUE;
	}
	else{
		$liked = FALSE;
	}
	dbClose($con);

	return TRUE;
}

function toggle_like ($user_id,$pin_id,$root_pin_id,$pic_id,&$err_msg){
	$con = dbConnect();
	$sql_count_likes = mysqli_prepare($con, 'SELECT count(*) count FROM likes WHERE `user_id`= ? AND `pin_id`= ?');
	if(!$sql_count_likes){
		$err_msg .= "Cannot prepare statement to count like".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_count_likes,'si',$user_id,$pin_id)){
		$err_msg .= "Cannot bind statement to count likec".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_count_likes)){
		$err_msg .= "Cannot execute statement to count like".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_bind_result($sql_count_likes,$count)){
		$err_msg .= "Cannot execute statement get count like".mysqli_stmt_error($sql_count_likes);
		dbClose($con);
		return FALSE;
	}

	mysqli_stmt_fetch($sql_count_likes);
	mysqli_stmt_close($sql_count_likes);
dbClose($con);

$con = dbConnect();
#--------------------------create/delete pins with pin_id and user_id-----------------------------------------------------
	if (!$count || $count == 0) {
		$sql_toggle_like = mysqli_prepare($con,"INSERT INTO likes(`pin_id`,`user_id`,`root_pin_id`) VALUES (?,?,?)");
	}
	else {
		$sql_toggle_like = mysqli_prepare($con,"DELETE FROM likes WHERE `pin_id` = ? AND `user_id` = ? AND `root_pin_id` = ?");
	}

	if(!$sql_toggle_like){
		$err_msg .= "Cannot prepare statement to toggle like".mysqli_stmt_error($sql_toggle_like);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_toggle_like,'isi',$pin_id,$user_id,$root_pin_id)){
		$err_msg .= "Cannot bind statement to toggle like".mysqli_stmt_error($sql_toggle_like);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_toggle_like)){
		$err_msg .= "Cannot execute statement to toggle like".mysqli_stmt_error($sql_toggle_like);
		dbClose($con);
		return FALSE;
	}

	dbClose($con);

$con = dbConnect();
#--------------------------------------------------------------------------
	
	$sql_update_llikes = mysqli_prepare($con,"UPDATE pins SET `local_likes` = (SELECT COUNT(*) from LIKES where `pin_id` = pins.`row_id`) WHERE `row_id` = ?");
	
	if(!$sql_update_llikes){
		$err_msg .= "Cannot prepare statement to update local like".mysqli_stmt_error($sql_update_llikes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_update_llikes,'i',$pin_id)){
		$err_msg .= "Cannot bind statement to update local likec".mysqli_stmt_error($sql_update_llikes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_update_llikes)){
		$err_msg .= "Cannot execute statement to update local like".mysqli_stmt_error($sql_update_llikes);
		dbClose($con);
		return FALSE;
	}

	dbClose($con);

$con = dbConnect();
#-----------------------------------------------------------------------------------------------
	
	$sql_update_tlikes = mysqli_prepare($con,"UPDATE pictures SET `total_likes` = (SELECT COUNT(*) from LIKES where `root_pin_id` = ?) WHERE `row_id` = ?");
	
	if(!$sql_update_tlikes){
		$err_msg .= "Cannot prepare statement to update total like".mysqli_stmt_error($sql_update_tlikes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_update_tlikes,'ii',$root_pin_id,$pic_id)){
		$err_msg .= "Cannot bind statement to update total likec".mysqli_stmt_error($sql_update_tlikes);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_update_tlikes)){
		$err_msg .= "Cannot execute statement to update total like".mysqli_stmt_error($sql_update_tlikes);
		dbClose($con);
		return FALSE;
	}

	dbClose($con);
	return TRUE;
}

function get_boards(&$boards, $conditions, $sort_by) {
	$con = dbConnect();
	$sql = <<<SQL
SELECT 
  board_name name,
  description description,
  comment_status cmnt_status,
  user_id uname,
  row_id board_id 
FROM
  pinboards 
WHERE row_id = row_id
SQL;

	for ($i = 0; $i < count($conditions); $i++) {
		$condition = $conditions[$i];
		$sql .= " AND " . $condition['column'] . " " . $condition['operation'] . " " . $condition['value'];
	}

	if ($sort_by != "") {
		$sql .= " SORT BY " . $sort_by;
	}

	$result = mysqli_query($con, $sql);
	if ($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
		$boards = $row;
	}
	
	dbClose($con);
}

function get_streams(&$streams, $conditions, $sort_by) {
	$con = dbConnect();
	$sql = <<<SQL
SELECT 
  name name,
  description description,
  keyword_query kquery,
  user_id uname,
  row_id stream_id 
FROM
  streams 
WHERE row_id = row_id
SQL;

	for ($i = 0; $i < count($conditions); $i++) {
		$condition = $conditions[$i];
		$sql .= " AND " . $condition['column'] . " " . $condition['operation'] . " " . $condition['value'];
	}

	if ($sort_by != "") {
		$sql .= " SORT BY " . $sort_by;
	}

	$result = mysqli_query($con, $sql);
	if ($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
		$streams = $row;
	}

	dbClose($con);
}

function create_pin($user_id,$tags,$url_pic,$url_site,$title,$description,$pinboard_id,$file_ext,&$pic_id,&$pin_id,&$err_msg){
	
	$con = dbConnect();

	$err_msg = "";
	$sql_create_board = mysqli_prepare ($con,
		"INSERT INTO pictures(`url_pic`,`url_site`,`user_id`,`title`,`description`) VALUES (?,?,?,?,?)");
	if(!$sql_create_board){
		$err_msg = "Cannot prepare statement to create Pic".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_create_board,'sssss',$url_pic,$url_site,$user_id,$title,$description)){
		$err_msg .= "Cannot bind statement to create Pic".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_create_board)){
		$err_msg .= "Cannot execute statement to create Pic".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	$pic_id = mysqli_insert_id($con);



	$sql_create_pin = mysqli_prepare($con,
		"INSERT INTO pins(`pinboard_id`,`user_id`,`tags`,`picture_id`) VALUES (?,?,?,?)");
	if(!$sql_create_pin){
		$err_msg .= "Cannot prepare statement to create Pin:".mysqli_stmt_error($sql_create_pin);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_create_pin,'issi',$pinboard_id,$user_id,$tags,$pic_id)){
		$err_msg .= "Cannot bind statement to create Pin".mysqli_stmt_error($sql_create_pin);;
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_create_pin)){
		$err_msg .= "Cannot execute statement to create Pin".mysqli_stmt_error($sql_create_pin);;
		return FALSE;
	}
	$pin_id = mysqli_insert_id($con);



	$sql_update_pic = mysqli_prepare ($con,
		"UPDATE pictures SET `file_name` = ? WHERE `row_id` = ?");
	if(!$sql_update_pic){
		$err_msg .= "Cannot prepare statement to update Pic".mysqli_stmt_error($sql_update_pic);
		return FALSE;
	}
	$file_name = $pic_id.".$file_ext";
	if(!mysqli_stmt_bind_param($sql_update_pic,'si',$file_name,$pic_id)){
		$err_msg .= "Cannot bind statement to update Pic".mysqli_stmt_error($sql_update_pic);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_update_pic)){
		$err_msg .= "Cannot execute statement to update Pic".mysqli_stmt_error($sql_update_pic);
		return FALSE;
	}

	$sql_update_pin = mysqli_prepare ($con,
		"UPDATE pins SET `parent_row_id` = `row_id` WHERE `row_id` = ?");
	if(!$sql_update_pin){
		$err_msg .= "Cannot prepare statement to update Pic".mysqli_stmt_error($sql_update_pin);
		return FALSE;
	}
	$file_name = $pic_id.".$file_ext";
	if(!mysqli_stmt_bind_param($sql_update_pin,'i',$pin_id)){
		$err_msg .= "Cannot bind statement to update Pic".mysqli_stmt_error($sql_update_pin);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_update_pin)){
		$err_msg .= "Cannot execute statement to update Pic".mysqli_stmt_error($sql_update_pin);
		return FALSE;
	}
	
	dbClose($con);
	return TRUE;
}

function create_board($uname,$name,$description,$comment_privacy,&$board_id,&$err_msg){
	
	$con = dbConnect();

	$err_msg = "";

	if (mysqli_num_rows(mysqli_query($con, "SELECT row_id FROM pinboards WHERE user_id='$uname' AND board_name='$name'")) > 0) {
		$err_msg = "Board already Exists";
		return FALSE;
	}

	$sql_create_board = mysqli_prepare ($con,
		"INSERT INTO pinboards(`board_name`,`user_id`,`comment_status`,`description`) VALUES (?,?,?,?)");
	if(!$sql_create_board){
		$err_msg = "Cannot prepare statement to create Board".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_create_board,'ssss',$name,$uname,$comment_privacy,$description)){
		$err_msg .= "Cannot bind statement to create Board".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_create_board)){
		$err_msg .= "Cannot execute statement to create Board".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	$board_id = mysqli_insert_id($con);
	
	dbClose($con);
	return TRUE;
}

function create_stream($uname,$name,$description,$keyword,&$stream_id,&$err_msg){
	
	$con = dbConnect();

	$err_msg = "";

	if (mysqli_num_rows(mysqli_query($con, "SELECT row_id FROM streams WHERE user_id='$uname' AND name='$name'")) > 0) {
		$err_msg = "Stream already Exists";
		return FALSE;
	}

	$sql_create_board = mysqli_prepare ($con,
		"INSERT INTO streams(`name`,`user_id`,`keyword_query`,`description`) VALUES (?,?,?,?)");
	if(!$sql_create_board){
		$err_msg = "Cannot prepare statement to create Stream".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_create_board,'ssss',$name,$uname,$keyword,$description)){
		$err_msg .= "Cannot bind statement to create Stream".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_create_board)){
		$err_msg .= "Cannot execute statement to create Stream".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	$stream_id = mysqli_insert_id($con);
	
	dbClose($con);
	return TRUE;
}

function delete_board($uname,$board_id,&$rows_affected,&$err_msg){
	
	$con = dbConnect();

	$err_msg = "";

	if (mysqli_num_rows(mysqli_query($con, "SELECT row_id FROM pinboards WHERE user_id='$uname' AND row_id='$board_id'")) == 0) {
		$err_msg = "Board doesn't Exists for User ID $uname";
		return FALSE;
	}

	$sql_delete_board = mysqli_prepare ($con,
		"Delete FROM pinboards WHERE `user_id`= ? AND `row_id`= ?");
	if(!$sql_delete_board){
		$err_msg = "Cannot prepare statement to delete Board".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_delete_board,'si',$uname,$board_id)){
		$err_msg .= "Cannot bind statement to delete Board".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_delete_board)){
		$err_msg .= "Cannot execute statement to delete Board".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	$rows_affected = mysqli_stmt_affected_rows($sql_delete_board);
	
	dbClose($con);
	return TRUE;
}

function delete_stream($uname,$stream_id,&$rows_affected,&$err_msg){
	
	$con = dbConnect();

	$err_msg = "";

	if (mysqli_num_rows(mysqli_query($con, "SELECT row_id FROM streams WHERE user_id='$uname' AND row_id='$stream_id'")) == 0) {
		$err_msg = "Stream doesn't Exists for User ID $uname";
		return FALSE;
	}

	$sql_delete_stream = mysqli_prepare ($con,
		"Delete FROM streams WHERE `user_id`= ? AND `row_id`= ?");
	if(!$sql_delete_stream){
		$err_msg = "Cannot prepare statement to delete Stream".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_delete_stream,'si',$uname,$stream_id)){
		$err_msg .= "Cannot bind statement to delete Stream".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_delete_stream)){
		$err_msg .= "Cannot execute statement to delete Stream".mysqli_stmt_error($sql_create_board);
		return FALSE;
	}
	$rows_affected = mysqli_stmt_affected_rows($sql_delete_stream);
	
	dbClose($con);
	return TRUE;
}

function create_repin($root_pin_id,$user_id,$tags,$title,$pinboard_id,&$pic_id,&$pin_id,&$err_msg){
	
	$con = dbConnect();

	$sql_check_pin = mysqli_prepare($con,
		"SELECT `row_id`,`picture_id`,count(*) count FROM PINS WHERE `row_id` = ?");
	if(!$sql_check_pin){
		$err_msg .= "Cannot prepare statement to check pin:".mysqli_stmt_error($sql_check_pin);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_check_pin,'i',$root_pin_id)){
		$err_msg .= "Cannot bind statement to check pin".mysqli_stmt_error($sql_check_pin);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_check_pin)){
		$err_msg .= "Cannot execute statement to check pin".mysqli_stmt_error($sql_check_pin);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_bind_result($sql_check_pin,$pin_id2,$pic_id,$count)){
		$err_msg .= "Cannot execute statement check pin".mysqli_stmt_error($sql_check_pin);
		dbClose($con);
		return FALSE;
	}

	mysqli_stmt_fetch($sql_check_pin);
	mysqli_stmt_close($sql_check_pin);
	dbClose($con);

	$con = dbConnect();

	$sql_create_pin = mysqli_prepare($con,
		"INSERT INTO pins(`pinboard_id`,`user_id`,`tags`,`picture_id`,`parent_row_id`) VALUES (?,?,?,?,?)");
	if(!$sql_create_pin){
		$err_msg .= "Cannot prepare statement to create Pin:".mysqli_stmt_error($sql_create_pin);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_create_pin,'issii',$pinboard_id,$user_id,$tags,$pic_id,$root_pin_id)){
		$err_msg .= "Cannot bind statement to create Pin".mysqli_stmt_error($sql_create_pin);;
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_create_pin)){
		$err_msg .= "Cannot execute statement to create Pin".mysqli_stmt_error($sql_create_pin);;
		return FALSE;
	}
	$pin_id = mysqli_insert_id($con);

	dbClose($con);
	return TRUE;
}

function unpin($pin_id,$user_id,&$pic_id,&$pic_fname,&$root_pin_id,&$err_msg){
	
	$con = dbConnect();

	$sql_check_pin = mysqli_prepare($con,
		"SELECT parent_row_id,picture_id,PICTURES.`file_name`,count(*) count FROM PINS,PICTURES WHERE PICTURES.`row_id` = PINS.`picture_id` AND PINS.`row_id` = ? AND PINS.`user_id` = ?");
	if(!$sql_check_pin){
		$err_msg .= "Cannot prepare statement to check pin:".mysqli_stmt_error($sql_check_pin);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_check_pin,'is',$pin_id,$user_id)){
		$err_msg .= "Cannot bind statement to check pin".mysqli_stmt_error($sql_check_pin);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_check_pin)){
		$err_msg .= "Cannot execute statement to check pin".mysqli_stmt_error($sql_check_pin);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_bind_result($sql_check_pin,$root_pin_id,$pic_id,$pic_fname,$count)){
		$err_msg .= "Cannot execute statement check pin".mysqli_stmt_error($sql_check_pin);
		dbClose($con);
		return FALSE;
	}
	mysqli_stmt_fetch($sql_check_pin);
	mysqli_stmt_close($sql_check_pin);
	if($count<=0){
		$err_msg = "User $user_id is not the owner of this Pin";
		dbClose($con);
		return FALSE;
	}
	dbClose($con);

	$con = dbConnect();

	$sql_delete_pin = mysqli_prepare($con,
		"DELETE FROM pins WHERE `row_id` = ?");
	if(!$sql_delete_pin){
		$err_msg .= "Cannot prepare statement to create Pin:".mysqli_stmt_error($sql_delete_pin);
		dbClose($con);
		return FALSE;
	}
	if(!mysqli_stmt_bind_param($sql_delete_pin,'i',$pin_id)){
		$err_msg .= "Cannot bind statement to create Pin".mysqli_stmt_error($sql_delete_pin);;
		return FALSE;
	}
	if(!mysqli_stmt_execute($sql_delete_pin)){
		$err_msg .= "Cannot execute statement to create Pin".mysqli_stmt_error($sql_delete_pin);;
		return FALSE;
	}
	$pin_id = mysqli_insert_id($con);

	dbClose($con);
	
	if($pin_id == $root_pin_id){
		$con = dbConnect();

		$sql_delete_pic = mysqli_prepare($con,
			"DELETE FROM pictures WHERE `row_id` = ?");
		if(!$sql_delete_pic){
			$err_msg .= "Cannot prepare statement to create Pin:".mysqli_stmt_error($sql_delete_pic);
			dbClose($con);
			return FALSE;
		}
		if(!mysqli_stmt_bind_param($sql_delete_pic,'i',$pic_id)){
			$err_msg .= "Cannot bind statement to delete Pic".mysqli_stmt_error($sql_delete_pic);;
			return FALSE;
		}
		if(!mysqli_stmt_execute($sql_delete_pic)){
			$err_msg .= "Cannot execute statement to delete Pic".mysqli_stmt_error($sql_delete_pic);;
			return FALSE;
		}
		$pin_id = mysqli_insert_id($con);

		dbClose($con);
	}

	return TRUE;
}

function get_comments($pin_id,&$comments) {
	$con = dbConnect();
	$return = FALSE;

	$sql = "SELECT message message, created created, pin_id pin_id, row_id comment_id FROM comments WHERE comments.pin_id = '$pin_id' SORT BY `CREATED` DESC";

	$result = mysqli_query($con, $sql);
	if ($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
		$comments = $row;
		$return = TRUE;
	}

	dbClose($con);
	return $return;
}

function create_comment($user_id,$message,$pin_id,&$comment_id,&$err_msg){
	
	$con = dbConnect();

	$err_msg = "";
	$sql_create_comment = mysqli_prepare ($con,
		"INSERT INTO comments(`user_id`,`message`,`pin_id`) VALUES (?,?,?)");

	if(!$sql_create_comment){
		$err_msg = "Cannot prepare statement to create Comment".mysqli_stmt_error($sql_create_comment);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_bind_param($sql_create_comment,'ssi',$user_id,$message,$pin_id)){
		$err_msg .= "Cannot bind statement to create Comment".mysqli_stmt_error($sql_create_comment);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_execute($sql_create_comment)){
		$err_msg .= "Cannot execute statement to create Comment".mysqli_stmt_error($sql_create_comment);
		dbClose($con);
		return FALSE;
	}
	
	$comment_id = mysqli_insert_id($con);
	dbClose($con);
	return TRUE;
}


//Bharath Queries
//########################################################################################################

function get_user_details($user)
{	
	$con = dbConnect();
	$sql = <<<SQL
SELECT
	user_id, fname, lname, email, gender, language, country
FROM
	user
WHERE '$_SESSION[uname]' = user_id
SQL;

	$DB = "dbproject";
	mysql_connect('localhost', 'root', '') or die(mysql_error());
	mysql_select_db("$DB") or die(mysql_error());

	$sql = <<<SQL
SELECT
	user_id, fname, lname, email, gender, language, country, password
FROM
	user
WHERE user_id = '$user';
SQL;

	$result = mysql_query($sql) or die(mysql_error());
	mysql_close();
	return $result;
}

function get_friends_list()
{
	//$con = dbConnect();
	$DB = "dbproject";
	mysql_connect('localhost', 'root', '') or die(mysql_error());
	mysql_select_db("$DB") or die(mysql_error());

	$sql = <<<SQL
select F.friend_id, fname, lname
from friends F join user U
where U.user_id = F.friend_id and F.user_id = '$_SESSION[uname]'
SQL;
	
	$result = mysql_query($sql) or die(mysql_error());
	mysql_close();
	return $result;
}


function invite_friend($user, $friend, $message, &$err_msg)
{
	$con = dbConnect();

	if (!$con) 
	{
		$err_msg = "Unable to connect to Database";
		return FALSE;
	}
	$result = FALSE;
	if($user == $friend)
	{
		$err_msg = "You cannot invite yourself";
		return FALSE;
	}
	if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM user WHERE USER_ID='$friend'"))== 0) 
	{
		$err_msg = $friend." does not exist";
		return FALSE;
	}
	if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM FRIENDS WHERE USER_ID='$user' AND FRIEND_ID='$friend'"))> 0) 
	{
		$err_msg = "You both are already friends!!";
		return FALSE;
	}
	if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM friend_request WHERE (user_id='$user' AND friend_id='$friend' AND status='Pending') OR (user_id='$friend' AND friend_id='$user' and status='Pending')") )> 0) 
	{
		$err_msg = "You or your friend has already sent the request";
		return FALSE;
	}
	if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM friend_request WHERE (user_id='$user' AND friend_id='$friend' AND status='Rejected') OR (user_id='$friend' AND friend_id='$user' and status='Rejected')") )> 0) 
	{
		if (!mysqli_query($con, "UPDATE friend_request SET STATUS='Pending' WHERE (user_id='$user' AND friend_id='$friend') OR (user_id='$friend' AND friend_id='$user')")) 
		{
			$err_msg = mysqli_error($con);
			$result = FALSE;
		} 
		else if (mysqli_affected_rows($con)) 
		{
		$result = TRUE;
		}
		dbClose($con);
		return $result;
	}
	if (!mysqli_query($con, "INSERT INTO friend_request(user_id, friend_id, message) VALUES ('$user', '$friend', '$message')")) 
	{
		$err_msg = mysqli_error($con);
		$result = FALSE;
	} 
	else 
		$result = TRUE;
	dbClose($con);
	return $result;
}




function remove_friend($user, $friend, &$err_msg)
{
	$con = dbConnect();

	if (!$con) 
	{
		$err_msg = "Unable to connect to Database";
		return FALSE;
	}
	$result = FALSE;

	$sql1 = <<<SQL1
DELETE FROM FRIENDS WHERE (USER_ID="$user" AND FRIEND_ID="$friend");
SQL1;
	$sql2 = <<<SQL2
DELETE FROM FRIENDS WHERE (USER_ID="$friend" AND FRIEND_ID="$user");
SQL2;

	if (!mysqli_query($con, $sql1)) 
	{
		$err_msg = mysqli_error($con);
		$result = FALSE;
	}
	elseif(!mysqli_query($con, $sql2)) 
	{
		$err_msg = mysqli_error($con);
		$result = FALSE;
	}
	else
	$result = TRUE;
	dbClose($con);
	return $result;
}

function reject_request($user, $friend, &$err_msg)
{
	$con = dbConnect();

	if (!$con) 
	{
		$err_msg = "Unable to connect to Database";
		return FALSE;
	}
	$result = FALSE;

	$sql = <<<SQL
UPDATE friend_request SET STATUS='Rejected' WHERE user_id='$friend' AND friend_id='$user';
SQL;

	if (!mysqli_query($con, $sql)) 
	{
		$err_msg = mysqli_error($con);
		$result = FALSE;
	}
	else
		$result = TRUE;
	dbClose($con);
	return $result;
}


function accept_request($user, $friend, &$err_msg)
{
	$con = dbConnect();

	if (!$con) 
	{
		$err_msg = "Unable to connect to Database";
		return FALSE;
	}
	$result = FALSE;

	$sql1 = <<<SQL1
INSERT INTO FRIENDS(USER_ID,FRIEND_ID) VALUES("$user", "$friend");
SQL1;
	$sql2 = <<<SQL2
INSERT INTO FRIENDS(USER_ID,FRIEND_ID) VALUES("$friend","$user");
SQL2;
	$sql3 = <<<SQL3
DELETE FROM FRIEND_REQUEST WHERE (USER_ID="$user" AND FRIEND_ID="$friend") OR (USER_ID="$friend" AND FRIEND_ID="$user");
SQL3;

	if (!mysqli_query($con, $sql1)) 
	{
		$err_msg = mysqli_error($con);
		$result = FALSE;
	}
	elseif(!mysqli_query($con, $sql2)) 
	{
		$err_msg = mysqli_error($con);
		$result = FALSE;
	}
	elseif(!mysqli_query($con, $sql3)) 
	{
		$err_msg = mysqli_error($con);
		$result = FALSE;
	}
	else
		$result = TRUE;
	dbClose($con);
	return $result;
}


function friend_requests($user, &$err_msg)
{
	$con = dbConnect();
	if (!$con) 
	{
		$err_msg = "Unable to connect to Database";
		return FALSE;
	}
	$result = mysqli_query($con, "SELECT user_id, status, message FROM friend_request WHERE friend_id='$user'");
	if (mysqli_num_rows($result)== 0) 
	{
		$err_msg = "No pending notifications";		
		return FALSE;
	}
	else 
		return $result;
}

function check_friendship($user, $friend, &$err_msg)
{
	$con = dbConnect();
	if (!$con) 
	{
		$err_msg = 0;
		return;
	}
	$result1=mysqli_query($con, "SELECT * FROM friends WHERE (user_id = '$user' AND friend_id = '$friend') OR (user_id = '$friend' AND friend_id = '$user')");
	if (mysqli_num_rows($result1)> 0) 
	{
		//Already friends
		$err_msg = 1;
		return;
	}
	$result2=mysqli_query($con, "SELECT * FROM friend_request WHERE user_id='$user' and friend_id='$friend' and status='Pending'");
	if (mysqli_num_rows($result2) > 0) 
	{
		//You have sent an invite
		$err_msg = 2;
		return;
	}
	$result3=mysqli_query($con, "SELECT * FROM friend_request WHERE friend_id='$user' and user_id='$friend' and status='Pending'");
	if (mysqli_num_rows($result3) > 0) 
	{
		//Invitation is sent by friend
		$err_msg = 3;
		return;
	}
	$result4=mysqli_query($con, "SELECT * FROM friend_request WHERE friend_id='$user' and user_id='$friend' and status='Rejected'");
	if (mysqli_num_rows($result4)> 0) 
	{
		//Not a friend BUT invitation is sent by friend AND you have rejected this request.
		$err_msg = 4;
		return;
	}
	$result5=mysqli_query($con, "SELECT * FROM friend_request WHERE (friend_id='$friend' AND user_id='$user') OR (friend_id='$user' AND user_id='$friend')");
	if (mysqli_num_rows($result5) == 0) 
	{
		//Not a friend AND no invite
		$err_msg = 5;
		return;
	}

	dbClose($con);

}



function update_user($uname, $pwd, $fname, $lname, $email, $gender, $language, $country, &$err_msg) {
	$con = dbConnect();

	if (!$con) {
		$err_msg = "Unable to connect to Database";
		return FALSE;
	}
	$result = FALSE;
	if (mysqli_num_rows(mysqli_query($con, "SELECT USER_ID FROM USER WHERE USER_ID='$uname'")) == 0) {
		$err_msg = "User Name does not exist";
		return FALSE;
	}

	if (!mysqli_query($con, "UPDATE USER SET PASSWORD ='$pwd',fname='$fname', lname = '$lname',email = '$email',gender = '$gender',LANGUAGE = '$language',country = '$country' WHERE (user_id = '$uname')")) {
		$err_msg = mysqli_error($con);
		$result = FALSE;
	} else 
		$result = TRUE;
	

	dbClose($con);
	return $result;
}

function follow_board($stream_id,$pinboard_id,&$err_msg)
{
	$con = dbConnect();
		$follow_board = mysqli_prepare ($con,
		"INSERT INTO follow_pinboard(`stream_id`,`pinboard_id`) VALUES (?,?)");

	if(!$follow_board){
		$err_msg = "Cannot prepare statement to follow stream".mysqli_stmt_error($follow_board);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_bind_param($follow_board,'ii',$stream_id,$pinboard_id)){
		$err_msg .= "Cannot bind statement to create Comment".mysqli_stmt_error($follow_board);
		dbClose($con);
		return FALSE;
	}

	if(!mysqli_stmt_execute($follow_board)){
		$err_msg .= "Cannot execute statement to create Comment".mysqli_stmt_error($follow_board);
		dbClose($con);
		return FALSE;
	}
	
	$follow_id = mysqli_insert_id($con);
	dbClose($con);
	return TRUE;
}
?>
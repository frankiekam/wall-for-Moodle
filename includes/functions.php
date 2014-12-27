<?php

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//
// Wall_Updates

$id = (int)$_GET['Id'];
class Wall_Updates

{
	var $cid;
	function Wall_Updates($w)
	{
		$this->cid = $w;
	}

	// Updates

	public

	function Updates($uid, $order)
	{

		// echo '$wcid is '.(int) $_GET['CourseId'];

		$wcid = (int)$_GET['CourseId'];
		$id = (int)$_GET['Id'];
		if ($order == 0)

		// Descending list! Use this for Facebook like Wall activity updates! LIFO!

		$query = mysql_query("SELECT M.course_id, M.activity_id, M.msg_id, M.uid_fk, M.message, M.created, U.username, U.firstname, U.lastname FROM messages_activity M, mdl_user U  WHERE M.uid_fk=U.id and M.course_id='$wcid' and M.activity_id='$id' order by M.msg_id desc ") or die(mysql_error());
		else

		// Ascending order! Use this for normal numbered lists!  FIFO!

		$query = mysql_query("SELECT M.course_id, M.activity_id, M.msg_id, M.uid_fk, M.message, M.created, U.username, U.firstname, U.lastname FROM messages_activity M, mdl_user U  WHERE M.uid_fk=U.id and M.course_id='$wcid' and M.activity_id='$id' order by M.msg_id") or die(mysql_error());
		$row = FALSE;
		$data = (array)null;
		while ($row = mysql_fetch_array($query)) $data[] = $row;
		return $data;
	}

	// Comments

	public

	function Comments($msg_id, $id)
	{

		// $wcid = (int) $_GET['CourseId'];
		// $wcid = $id;

		$wcid = $this->cid;
		$query = mysql_query("SELECT C.course_id, C.com_id, C.uid_fk, C.comment, C.created, U.username, U.firstname, U.lastname FROM comments_activity C, mdl_user U WHERE C.uid_fk=U.id and C.msg_id_fk='$msg_id' and C.course_id='$wcid' order by C.com_id asc ") or die(mysql_error());
		while ($row = mysql_fetch_array($query)) $data[] = $row;
		if (!empty($data)) {
			return $data;
		}
	}

	// Avatar Image

	public

	function Gravatar($uid)
	{
		$query = mysql_query("SELECT email FROM `mdl_user` WHERE id = '$uid'") or die(mysql_error());
		$row = mysql_fetch_array($query);
		if (!empty($row)) {
			$email = $row['email'];
			$lowercase = strtolower($email);
			$imagecode = md5($lowercase);

			// $data="http://www.gravatar.com/avatar.php?gravatar_id=$imagecode";
			// $data = "../user/profile.php/".$uid."/f2.jpg";

			$ustring = "select * from mdl_user where id = '2'";
			$user = $DB->get_record_sql($ustring);
			$data = $OUTPUT->user_picture($user, array(
				'size' => 136
			));
			return $data;
		}
		else {
			$data = "default.jpg";
			return $data;
		}
	}

	// Insert Update

	public

	function Insert_Update($w, $uid, $id, $update /*, $datestamp*/) 
	{
		//echo $update;
		
		$this->cid = $w;
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = mysql_query("SELECT msg_id,message FROM `messages_activity` WHERE uid_fk='$uid' order by msg_id desc limit 1") or die(mysql_error());
		$result = mysql_fetch_array($query);
		if ($update != $result['message']) {
			$query = mysql_query("INSERT INTO `messages_activity` (course_id, activity_id, message, uid_fk, ip,created) VALUES ('$w','$id', '$update', '$uid', '$ip','$time')") or die(mysql_error());
			$newquery = mysql_query("SELECT M.msg_id, M.uid_fk, M.message, M.created, M.activity_id, U.username FROM messages_activity M, mdl_user U where M.uid_fk=U.id and M.uid_fk='$uid' order by M.msg_id desc limit 1 ");
			$result = mysql_fetch_array($newquery);
			return $result;
		}
		else {
			return false;
		}
	}

	// Delete update

	public

	function Delete_Update($uid, $msg_id)
	{
		$query = mysql_query("DELETE FROM `comments_activity` WHERE msg_id_fk = '$msg_id' ") or die(mysql_error());
		$query = mysql_query("DELETE FROM `messages_activity` WHERE msg_id = '$msg_id'") or die(mysql_error());
		return true;
	}

	// Insert Comments

	public

	function Insert_Comment($w, $uid, $msg_id, $id, $comment)
	{
		$this->cid = $w;
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = mysql_query("SELECT course_id, com_id,comment FROM `comments_activity` WHERE uid_fk='$uid' and msg_id_fk='$msg_id' and course_id='$w' order by com_id desc limit 1 ") or die(mysql_error());
		$result = mysql_fetch_array($query);
		if ($comment != $result['comment']) {
			$query = mysql_query("INSERT INTO `comments_activity` (course_id, activity_id, comment, uid_fk,msg_id_fk,ip,created) VALUES ('$w', '$id', '$comment', '$uid','$msg_id', '$ip','$time')") or die(mysql_error());
			$newquery = mysql_query("SELECT C.course_id, C.activity_id, C.com_id, C.uid_fk, C.comment, C.msg_id_fk, C.created, U.username FROM comments_activity C, mdl_user U where C.uid_fk=U.id and C.uid_fk='$uid' and C.msg_id_fk='$msg_id' and C.course_id='$w' and C.activity_id='$id' order by C.com_id desc limit 1 ");
			$result = mysql_fetch_array($newquery);
			return $result;
		}
		else {
			return false;
		}
	}

	// Delete Comments

	public

	function Delete_Comment($com_id)
	{
		$query = mysql_query("DELETE FROM `comments_activity` WHERE com_id='$com_id'") or die(mysql_error());
		return true;
	}
}

?>
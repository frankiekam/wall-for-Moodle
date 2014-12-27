<?php

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//
// Loading Comments link with load_updates.php

function time_stamp($session_time)
{
	$time_difference = time() - $session_time;
	$seconds = $time_difference;
	$minutes = round($time_difference / 60);
	$hours = round($time_difference / 3600);
	$days = round($time_difference / 86400);
	$weeks = round($time_difference / 604800);
	$months = round($time_difference / 2419200);
	$years = round($time_difference / 29030400);
	if ($seconds <= 60) {
		echo "$seconds seconds ago";
	}
	else
	if ($minutes <= 60) {
		if ($minutes == 1) {
			echo "one minute ago";
		}
		else {
			echo "$minutes minutes ago";
		}
	}
	else
	if ($hours <= 24) {
		if ($hours == 1) {
			echo "one hour ago";
		}
		else {
			echo "$hours hours ago";
		}
	}
	else
	if ($days <= 7) {
		if ($days == 1) {
			echo "one day ago";
		}
		else {
			echo "$days days ago";
		}
	}
	else
	if ($weeks <= 4) {
		if ($weeks == 1) {
			echo "one week ago";
		}
		else {
			echo "$weeks weeks ago";
		}
	}
	else
	if ($months <= 12) {
		if ($months == 1) {
			echo "one month ago";
		}
		else {
			echo "$months months ago";
		}
	}
	else {
		if ($years == 1) {
			echo "one year ago";
		}
		else {
			echo "$years years ago";
		}
	}
}

?>
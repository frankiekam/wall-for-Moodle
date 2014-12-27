<?php
/*

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//

*/
error_reporting(0);
include_once 'includes/db.php';

include_once 'includes/functions.php';

include_once 'includes/tolink.php';

include_once 'includes/time_stamp.php';

include_once 'session.php';

$Wall = new Wall_Updates();

if (isSet($_POST['msg_id'])) {
	$msg_id = $_POST['msg_id'];

	// $data=$Wall->Delete_Update($uid,$msg_id);

	$data = $Wall->Delete_Update($uid, $msg_id);
	echo $data;
}

?>
 <?php
require_once ('../config.php');

require_once ($CFG->dirroot . '/course/lib.php');

require_once ($CFG->libdir . '/filelib.php');

redirect_if_major_upgrade_required();

if ($CFG->forcelogin) {
	require_login();
}
else {
	user_accesstime_log();
}

$hassiteconfig = has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM));
$PAGE->set_url('/');
$PAGE->set_course($SITE);
$uid = $USER->id;
?>
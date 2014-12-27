<?php
/* For this to work, this file must be in a child folder of the Moodle folder.
For example, this file should be inside:

/public_html/moodle/wall

where moodle is the parent folder and wall is the child folder.
*/
require "../config.php";

define('DB_SERVER', $CFG->dbhost);
define('DB_USERNAME', $CFG->dbuser);
define('DB_PASSWORD', $CFG->dbpass);
define('DB_DATABASE', $CFG->dbname);
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
$database = mysql_select_db(DB_DATABASE) or die(mysql_error());
/* TABLES FOR THE CONTENT AND THE RATINGS (MODIFY IF TABLE NAMES ARE DIFFERENT) */
$content = 'content';
$ratings = 'ratings';
$ip = $_SERVER["REMOTE_ADDR"]; //IP ADDRESS

?>
SETUP INSTRUCTIONS FOR MOODLE WONDERWALL FOR MOODLE 2.X

Tested on Moodle 2.7.2 on 29th October 2014.
It should also work on almost all Moodle 2.x versions.


SETUP INSTRUCTIONS
------------------

1. Extract the contents of wall.zip into a folder wall on your local PC.

2. There is no need to specify your MySQL username and password inside the file includes/db.php as this code
   automatically detects your MySQL settings!

   require "../config.php";
 
   define('DB_SERVER', $CFG->dbhost);
   define('DB_USERNAME', $CFG->dbuser);
   define('DB_PASSWORD', $CFG->dbpass);
   define('DB_DATABASE', $CFG->dbname );
   $connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
   $database = mysql_select_db(DB_DATABASE) or die(mysql_error());
   
   So there is nothing to do in section 2 (here, this section!). Move on to section 3 below.
   

3. Import the SQL instructions found inside the file wall.sql

   If you have phpMyAdmin or Moodle Adminer installed, then this process is quite simple. 
   Otherwise, you might want to use a tool like MYSQL Wizard or the phpMyAdmin provided by your CPanel.
	
   This import action will create these 4 SQL tables:

   comments_activity
   messages_activity
   content
   ratings

   Your Moodle database, by default, does not contain any SQL tables named as such. 
   Therefore your import process should be trouble-free.	
   See the Appendix Section for the full code of wall.sql.
	
	
4. Unzip wall.zip so that there is a folder named wall. 
   Ftp the folder wall to public_html/moodle. For example, the Wall folder is the child of the moodle folder. I.e., 

   public_html/moodle/wall
	

5. Edit the file includes/functions.php

   Change the identifier: mdl_user to the name of your actual Moodle 2.2/2.3/2.4/2.5/2.6/2.7 user table.
   For example, if your user table is named m232_user, then change all occurrences of mdl_user to m232_user.

   Check these lines of the includes/functions.php file:

   Line 30:   $query = mysql_query("SELECT M.course_id, M.activity_id, M.msg_id, M.uid_fk, M.message, M.created, U.username, U.firstname, U.lastname FROM messages_activity M, mdl_user U  WHERE M.uid_fk=U.id and M.course_id='$wcid' and M.activity_id='$id' order by M.msg_id desc ") or die(mysql_error());

   Line 33:  $query = mysql_query("SELECT M.course_id, M.activity_id, M.msg_id, M.uid_fk, M.message, M.created, U.username, U.firstname, U.lastname FROM messages_activity M, mdl_user U  WHERE M.uid_fk=U.id and M.course_id='$wcid' and M.activity_id='$id' order by M.msg_id") or die(mysql_error());

   Line 47:   $query = mysql_query("SELECT C.course_id, C.com_id, C.uid_fk, C.comment, C.created, U.username, U.firstname, U.lastname FROM comments_activity C, mdl_user U WHERE C.uid_fk=U.id and C.msg_id_fk='$msg_id' and C.course_id='$wcid' order by C.com_id asc ") or die(mysql_error());

   Line 63:   $query = mysql_query("SELECT email FROM `mdl_user` WHERE id = '$uid'") or die(mysql_error());

   Line 74:   $ustring = "select * from mdl_user where id = '2'";

   Line 102:   $newquery = mysql_query("SELECT M.msg_id, M.uid_fk, M.message, M.created, M.activity_id, U.username FROM messages_activity M, mdl_user U where M.uid_fk=U.id and M.uid_fk='$uid' order by M.msg_id desc limit 1 ");

   Line 138:   $newquery = mysql_query("SELECT C.course_id, C.activity_id, C.com_id, C.uid_fk, C.comment, C.msg_id_fk, C.created, U.username FROM comments_activity C, mdl_user U where C.uid_fk=U.id and C.uid_fk='$uid' and C.msg_id_fk='$msg_id' and C.course_id='$w' and C.activity_id='$id' order by C.com_id desc limit 1 ");


6. In these 4 files,

   comment_ajax.php 
   load_comments.php
   load_messages.php
   message_ajax.php

   rename the mdl_user (User table name) to your MySQL user table name.

   If your user table name is already mdl_user, then there is nothing for you to change. 
   Otherwise, if your user table name is mdl_m2_user, then change all occurrences from mdl_user to mdl_m2_user.

   6.1   comment_ajax.php (1 hit)
   Line 321:  $ustring = "select * from mdl_user where id = ".$uid;

   6.2   load_comments.php (1 hit)
   Line 291:  $ustring = "select * from mdl_user where id = ".$uid;

   6.3   load_messages.php (3 hits)
   Line 411:  $ustring = "select * from mdl_user where id = ".$uid;
   Line 645:  //$ustring = "select * from mdl_user where id = ".$uid;
   Line 646:  $ustring = "select * from mdl_user where id = ".$USER->id;

   6.4   message_ajax.php (3 hits)
   Line 379:  $ustring = "select * from mdl_user where id = ".$uid;
   Line 666:  //$ustring = "select * from mdl_user where id = ".$uid;
   Line 667:  $ustring = "select * from mdl_user where id = ".$USER->id;




7. Create a webpage, block, resource where you can go to HTML Mode. In HTML Mode, paste in this code:

<center> 
<iframe width="100%" scrolling="auto" height="700px" frameborder="0" align="middle" name="Embedded Frame" src="http://www.yourwebsite.com/moodle/wall/index.php?Order=0&amp;CourseId=5&amp;Id=2&amp;Likes=1&amp;AdminOnly=0&amp;Email=0&amp;Desc=Wall" marginheight="4px" marginwidth="4px"></iframe><p></p>
</center>



After you have saved the webpage resource, you may just see a blank page. If that happens, just do a browser refresh (F5) and the wall should appear after a couple of seconds.

If nothing happened, 
1. check that the wall subfolder is inside your moodle folder.
2. check that your user table name is mdl_user.
3. check your debugging information by setting your moodle site's debugging mode to "show all errors".
4. Still can't solve it? Email Frankie Kam at: boonsengkam@gmail.com


iFRAME PARAMETERS EXPLANATION AND GUIDE
---------------------------------------

&Order=0  --> Display wall posts as normal social wall format. I.e., default FIFO mode. Most recent post at top of the wall.

&Order=1  --> Display wall posts as reverse social wall format. I.e., as LIFO mode. Last post at top of the wall.

&CourseId=28  --> supposed to be the same value as your Moodle course id. In actual fact, any number will do! You can change 28 to 1020 for example. 

&Id=1         --> any number will do. LOL! This MUST be set in tandem with &CourseId=28 for example.

&Likes=1      --> Display Like button

&Likes=0      --> Hide Like button

&AdminOnly=0  --> Anyone can post to the wall. Admin, students.

&AdminOnly=1  --> Only the admin or course administrator can post to the wall. Not students. A kind of read-only wall. This is useful when the teacher just wants students to read wall messages and not allow them to post. However students can still post comments on messages.

&Email=0     ---> No E-mail notifications are sent out. Default mode.

&Email=2     ---> be careful with this one. If your Moodle user id is 2, then you will be inundated with E-mails sent by the Moodle server to your E-mail. The value at the end of &Email=  is the Moodle user id.

&Journal=1   ---> Enables 'Moodle Journal' mode where a user only sees messages from himself/herself and the admin (teacher). 
                  Other student messages are hidden from him/her. This is similar to the Moodle "Q&A Forum". Useful in cases where the 
				  instructor/teacher does not want students to copy each other's responses.

&Desc=Announcements  --> Whatever is typed after &Desc will appear on the Wall page top.


Bug Note: as of 7th December 2014, there is a bug with the Email feature. It doesn't seem to work in Moodle 2.x! Perhaps you can help me to debug and to correct this. Frankie Kam.

ADDITIONAL NOTES
----------------

Your copy of the Wall code is exactly the same as this one:

http://www.cambridgekids.org/moodle/course/view.php?id=5
Username: demo
Password: demo   


For rich text (Media), enter some content in the yellow edit box and then click on the top left-most Diskett icon ("Save Content").
For normal text, enter some content in the yellow edit box and then click on the blue rectangular Submit button.

To change the background colour of the yellow edit box, from yellow to, say, white, edit the file js/nicEdit-wall.js and
search for the code
backgroundColor:"#FFFAA5"
and change the colour code from FFFAA5 to FFFFFF. I.e., backgroundColor:"#FFFFFF" 

For more stuff that the Wall can do, refer to these blogposts.
http://moodurian.blogspot.com/2012/10/wallify-your-moodle-19-and-2x-coursepage.html
http://moodurian.blogspot.com/2013/08/facebook-like-wall-for-moodle-19x-and.html
http://moodurian.blogspot.com/2014/10/wall-for-moodle-2x-has-been-enhanced.html

You can E-mail me if there are any problems.


Regards
Frankie Kam
boonsengkam@gmail.com


ADDITIONAL ADVANCED INFO
========================

You can even have a separate individual Wall for EVERY course!
All you need to do is to edit your 
/public_html/course/format/<courseformatname>/format.php

For example, on my system I have
/public_html/course/format/weekcoll/format.php

What I did was to add the below iframe code in between the 
print_container_start();
and
echo skip_main_destination(); 
lines.


For example below:-

...
    print_container_start();
 
 	/* Wall code added by Frankie on 6th October 2014. */
	echo '<center><iframe style="border: 1px" width="100%" scrolling="auto" height="920px" frameborder="1" align="left" marginwidth="5px" marginheight="5px" src="http://www.yourdomain.com/moodle/wall/index.php?CourseId='.$COURSE->id.'&Id=1&Likes=1&LikesDislikes=0&Order=0&AdminOnly=1&Desc=Social Wall"></iframe></center>';	


    echo skip_main_destination(); 
...


I also use the Collapsible topic format a lot on my site.
/public_html/course/format/topcoll/format.php

What I did was to add the below iframe code towards the end of the format.php. See below for reference.

    ...
    // Establish horizontal unordered list for horizontal columns.
    if ($tcsettings['layoutcolumnorientation'] == 2) {
        echo '.course-content ul.ctopics li.section {';
        echo 'display: inline-block;';
        echo 'vertical-align:top;';
        echo '}';
        echo 'body.ie7 .course-content ul.ctopics li.section {';
        echo 'zoom: 1;';
        echo '*display: inline;';
        echo '}';
    }
    ?>;
    /* ]]> */
    </style>
    <?php

	/* Wall code inserted by Frankie on 6th October 2014. */
	echo '<center><iframe style="border: 1px" width="100%" scrolling="auto" height="920px" frameborder="1" align="left" marginwidth="5px" marginheight="5px" src="http://www.yourdomain/moodle/wall/index.php?CourseId='.$COURSE->id.'&Id=1&Likes=1&LikesDislikes=0&Order=0&AdminOnly=1&Desc=Social Wall"></iframe></center>';	
	/* End of Wall code inserted by Frankie on 6th October 2014. */
	
    $renderer->print_multiple_section_page($course, null, null, null, null);

}

// Include course format js module.
$PAGE->requires->js('/course/format/topcoll/format.js');




End of setup instructions. 
Frankie Kam (c) October 2014

APPENDIX - WALL.SQL

-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2011 at 07:21 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wall`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments_activity`
--

CREATE TABLE IF NOT EXISTS `comments_activity` (
  `course_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(20000) DEFAULT NULL,
  `msg_id_fk` int(11) DEFAULT NULL,
  `uid_fk` int(11) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `created` int(11) DEFAULT '1269249260',
  PRIMARY KEY (`com_id`),
  KEY `msg_id_fk` (`msg_id_fk`),
  KEY `uid_fk` (`uid_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;


-- --------------------------------------------------------

--
-- Table structure for table `messages_activity`
--

CREATE TABLE IF NOT EXISTS `messages_activity` (
  `course_id` int(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(20000) DEFAULT NULL,
  `uid_fk` int(11) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `created` int(11) DEFAULT '1269249260',
  PRIMARY KEY (`msg_id`),
  KEY `uid_fk` (`uid_fk`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

--
-- Table structure for table `content` and `ratings`
--

CREATE TABLE IF NOT EXISTS  `content` (
`id` INT NOT NULL ,
`content` TEXT NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE IF NOT EXISTS  `ratings` (
`rating` VARCHAR ( 7 ) NOT NULL ,
`id` INT NOT NULL ,
`ip` VARCHAR ( 50 ) NOT NULL ,
`userid_rating` INT NOT NULL
) ENGINE = MYISAM ;

-- --------------------------------------------------------
-- See this link!
-- https://moodle.org/mod/forum/discuss.php?d=266676


End of Readme guide file.
Last updated on 29th October 2014 by Frankie Kam.

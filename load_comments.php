<?php
/*

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//
// Loading Comments link with load_updates.php

*/

foreach((array)$commentsarray as $cdata) {
	$com_id = $cdata['com_id'];
	$comment = tolink(htmlentities($cdata['comment']));
	$comment = $cdata['comment'];
	$comment = str_replace('  ', ' &nbsp;', $comment);
	$comment = str_replace('	', '&nbsp;&nbsp;&nbsp;&nbsp;', $comment);
	$comment = str_replace("\t", '&nbsp;&nbsp;&nbsp;', $comment);
	$comment = str_replace("kxk", "&", $comment);
	$comment = str_replace('qxq', '+', $comment);
	$comment = str_replace('&gt;', '>', $comment);
	$comment = str_replace('&lt;', '<', $comment);
	$comment = str_replace('&quot;', '"', $comment);
	$comment = str_replace('&Acirc;', '&nbsp;', $comment);
	$comment = str_replace('wzw', '&amp;', $comment);
	$comment = str_replace('&amp;amp;', '&amp;', $comment);
	if (strpos($comment, 'http://youtu.be/') === false);
	else $comment = str_replace("youtu.be/", "www.youtube.com/watch?v=", $comment);
	$findme1 = 'https://www.youtube.com/watch?v=';
	$pos1 = strpos($comment, $findme1);
	$findme2 = 'http://www.youtube.com/watch?v=';
	$pos2 = strpos($comment, $findme2);
	if (($pos1 === false) && ($pos2 === false)) {
		$findme = 'www.ted.com/talks';
		$pos = strpos($comment, $findme);
		if ($pos === false) {

			// look for a jpg, gif, or png reference
			// if not found, carry on as normal

			$pos = stripos($comment, ".jpg") || $pos = stripos($comment, ".png") || $pos = stripos($comment, ".gif");
			if ($pos === false) {
				$findme = 'https://voicethread.com/share/';
				$pos = strpos($comment, $findme);
				if ($pos === false) {
					$findme = 'http://teachertube.com/viewVideo.php?video_id=';
					$pos = strpos($comment, $findme);
					if ($pos === false) {
						$findme = 'http://www.slideshare.net';
						$pos = strpos($comment, $findme);
						if ($pos === false) {
							$findme = 'http://www.scribd.com/doc/';
							$pos = strpos($comment, $findme);
							if ($pos === false) {
								$findme = 'http://vimeo.com';
								$pos = strpos($comment, $findme);
								if ($pos === false) {
									$comment = tolink(htmlentities(nl2br($comment)));
								}
								else {
									$oriMesg = $comment;
									$comment = str_replace("vimeo.com/", "www.youtube.com/watch?v=", $comment);
									$arr = explode("https://www.youtube.com", $comment, 2);
									$first = $arr[0];
									$url = $comment;
									$codeid = 0;

									// we get the unique video id from the url by matching the pattern

									preg_match("/v=([^&]+)/i", $url, $matches);
									if (isset($matches[1])) $codeid = $matches[1];
									if (!$id) {
										$matches = explode('/', $url);
										$codeid = $matches[count($matches) - 1];
									}

									// this is your template for generating embed codes

									$code = '<iframe src="http://player.vimeo.com/video/{id}" width="300" height="133" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

									// we replace each {id} with the actual ID of the video to get embed code for this particular video

									$code = str_replace('{id}', $codeid, $code);
									$comment = tolink(htmlentities(nl2br($oriMesg))) . "<p><br />" . $code;
								}
							}
							else {
								$oriMesg = $comment;
								$comment = str_replace("http://www.scribd.com/doc/", "www.youtube.com/watch?v=", $comment);
								$arr = explode("https://www.youtube.com", $comment, 2);
								$first = $arr[0];
								$url = $comment;
								$codeid = 0;

								// we get the unique video id from the url by matching the pattern

								preg_match("/v=([^&]+)/i", $url, $matches);
								if (isset($matches[1])) $codeid = $matches[1];
								if (!$id) {
									$matches = explode('/', $url);
									$codeid = $matches[count($matches) - 1];
								}

								// this is your template for generating embed codes

								$code = '<a href="http://www.scribd.com/doc/{id}" style="margin: 12px auto 6px auto; font-family: Helvetica,Arial,Sans-serif; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none; display: block; text-decoration: underline;"></a><iframe class="scribd_iframe_embed" src="http://www.scribd.com/embeds/{id}/content?start_page=1&view_mode=scroll" data-auto-height="true" data-aspect-ratio="" scrolling="no" width="100%" height="600" frameborder="0"></iframe>';

								// we replace each {id} with the actual ID of the video to get embed code for this particular video

								$code = str_replace('{id}', $codeid, $code);
								$comment = tolink(htmlentities(nl2br($oriMesg))) . "<p><br />" . $code;
							}
						}
						else {
							$oriMesg = $comment;
							$comment = str_replace("http://www.slideshare.net/", "www.youtube.com/watch?v=", $comment);
							$arr = explode("https://www.youtube.com", $comment, 2);
							$first = $arr[0];
							$url = $comment;
							$codeid = 0;

							// we get the unique video id from the url by matching the pattern

							preg_match("/v=([^&]+)/i", $url, $matches);
							if (isset($matches[1])) $codeid = $matches[1];
							if (!$id) {
								$matches = explode('/', $url);
								$codeid = $matches[count($matches) - 1];
							}

							// this is your template for generating embed codes

							$code = '<iframe src="http://www.slideshare.net/slideshow/embed_code/{id}" width="427" height="356" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border:1px solid #CCC;border-width:1px 1px 0;margin-bottom:5px" allowfullscreen> </iframe> ';

							// we replace each {id} with the actual ID of the video to get embed code for this particular video

							$code = str_replace('{id}', $codeid, $code);
							$comment = tolink(htmlentities(nl2br($oriMesg))) . "<p><br />" . $code;
						}
					}
					else {
						/* Embed a TeacherTube video! */
						$oriMesg = $comment;
						$comment = str_replace("teachertube.com/viewVideo.php?video_id=", "www.youtube.com/watch?v=", $comment);
						$arr = explode("https://www.youtube.com", $comment, 2);
						$first = $arr[0];
						$url = $comment;
						$codeid = 0;

						// we get the unique video id from the url by matching the pattern

						preg_match("/v=([^&]+)/i", $url, $matches);
						if (isset($matches[1])) $codeid = $matches[1];
						if (!$id) {
							$matches = explode('/', $url);
							$codeid = $matches[count($matches) - 1];
							$codeid = str_replace("watch?v=", "", $codeid);
						}

						// this is your template for generating embed codes

						$code = '<embed flashvars="file=http://teachertube.com/embedFLV.php?pg=video_{id}"
                                            allowfullscreen="true"
                                            allowscriptaccess="always"
                                            id="player1"
                                            name="player1"
                                            src="http://teachertube.com/embed/player.swf"
                                            width="480"
                                            height="270"
                                          />  ';

						// we replace each {id} with the actual ID of the video to get embed code for this particular video

						$code = str_replace('{id}', $codeid, $code);
						$comment = tolink(htmlentities(nl2br($oriMesg))) . "<p><br />" . $code;
					}
				}
				else {
					/* Embed a Voicethread! */
					$orimesg = $comment;
					/* Voicethread! */

					// Check if the final character of the URL is a frontslash. If yes, nuke it!

					if (substr($comment, -1) == '/') {
						$comment = rtrim($comment, '/');

						// $comment = preg_replace('{/$}', '', $comment);

					}

					$comment = str_replace("voicethread.com/share/", "www.youtube.com/watch?v=", $comment);
					$arr = explode("https://www.youtube.com", $comment, 2);
					$first = $arr[0];
					$url = $comment;
					$codeid = 0;

					// we get the unique video id from the url by matching the pattern

					preg_match("/v=([^&]+)/i", $url, $matches);
					if (isset($matches[1])) $codeid = $matches[1];
					if (!$id) {
						$matches = explode('/', $url);
						$codeid = $matches[count($matches) - 1];
					}

					// this is your template for generating embed codes

					$code = '<object width="480" height="360"><param name="movie" value="https://voicethread.com/book.swf?b={id}"></param><param name="wmode" value="transparent"></param><embed src="https://voicethread.com/book.swf?b={id}" type="application/x-shockwave-flash" wmode="transparent" width="480" height="360"></embed></object>';

					// we replace each {id} with the actual ID of the video to get embed code for this particular video

					$code = str_replace('{id}', $codeid, $code);
					$comment = tolink(htmlentities(nl2br($oriMesg))) . "<p><br />" . $code;
				}
			}
			else {
				/* Embed an image! */
				$beg = stripos($comment, "http://");
				if ($beg === false) $beg = stripos($comment, "www.");
				if ($beg === false) $beg = stripos($comment, "ftp.");
				if ($beg !== false) {
					$imgsrc = '$beg=' . $beg;
					$end = stripos($comment, ".jpg");
					if ($end === false) $end = stripos($comment, ".gif");
					if ($end === false) $end = stripos($comment, ".png");
					if ($end !== false) {
						$imgsrc = substr($comment, $beg, $end - $beg + 4);
					}
				}

				$width = 200;
				$target = " onclick=\"window.open(this.href,\\'_blank\\');return false;\"";
				$comment = preg_replace("#(^|[\n \]])([\w]+?://[\w\#$%&~/.\-;:=,?@+]*)#ise", "'\\1<insertimage><P><center><a href=\'" . $imgsrc . "\'" . $target . "><img src=\'\\2\' width=\'" . $width . "\'></img></a></center></insertimage>'", $comment);
				$comment = nl2br($comment);
			}
		}
		else {
			/* Embed a TED.com video! */
			$httpsExists = strpos($comment, "https");
			if ($httpsExists !== false) $arr = explode("https://www.ted.com/talks/", $comment, 2);
			else $arr = explode("http://www.ted.com/talks/", $comment, 2);
			$first = $arr[0];
			$second = $arr[1];
			$third = "";
			$NewLineExists = strpos($second, "\n");
			if ($NewLineExists !== false) {
				$first = str_replace("\n", " ", $first);
				$nexthalf = explode("\n", $second, 2);
				$second = $nexthalf[0];
				$third = $nexthalf[1];
			}
			else {
				$SpaceExists = strpos($second, " ");
				if ($SpaceExists !== false) {
					$nexthalf = explode(" ", $second, 2);
					$second = $nexthalf[0];
					$third = $nexthalf[1];
				}
			}

			$TED_talk_name = $second;
			$TED_trailing_text = $third;
			$comment = $first . '<p>' . '<div id="img_wrapper">
				<iframe src="https://embed-ssl.ted.com/talks/' . $TED_talk_name . '.html" width="391" height="220" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
					</div>' . '<br />' . $TED_trailing_text;
		}
	}
	else {
		/* Embed a Youtube video! */
		$httpExists = strpos($comment, "http:");
		if ($httpExists !== false) $comment = str_replace("http:", "https:", $comment);
		$arr = explode("https://www.youtube.com/watch?v=", $comment, 2);
		$first = $arr[0];
		$second = $arr[1];
		$third = "";
		$NewLineExists = strpos($second, "\n");
		if ($NewLineExists !== false) {
			$first = str_replace("\n", " ", $first);
			$nexthalf = explode("\n", $second, 2);
			$second = $nexthalf[0];
			$third = $nexthalf[1];
		}
		else {
			$SpaceExists = strpos($second, " ");
			if ($SpaceExists !== false) {
				$nexthalf = explode(" ", $second, 2);
				$second = $nexthalf[0];
				$third = $nexthalf[1];
			}
		}

		$Youtube_id = $second;
		$Youtube_trailing_text = $third;
		$comment = $first . '<p>' . '<div id="img_wrapper">
				<iframe class="youtube-player" type="text/html" width="391" height="220" src="https://www.youtube.com/embed/' . $Youtube_id . '" frameborder="0">
				</iframe></div>' . '<p>' . $Youtube_trailing_text;
	}

	$time = $cdata['created'];

	// $username=$cdata['username'];

	$username = $cdata['firstname'] . ' ' . $cdata['lastname'];
	$uid = $cdata['uid_fk'];

	// $cface=$Wall->Gravatar($uid);
	// $cface="http://www.moodurian.com/user/pix.php/".$uid."/f2.jpg";

?>



<div class="stcommentbody" id="stcommentbody<?php
	echo $com_id; ?>">
<div class="stcommentimg">
<?php
	$ustring = "select * from mdl_user where id = " . $uid;
	$user = $DB->get_record_sql($ustring);
	$cface = $OUTPUT->user_picture($user, array(
		'size' => 36
	));
	echo $cface;
?>
</div>
<div class="stcommenttext">
<div style="white-space:nowrap;"
<a class="stcommentdelete" href="#" id='<?php
	echo $com_id; ?>' title="Delete Comment">
<?php
	$admins = get_admins();
	$isadmin = false;
	foreach($admins as $admin) {
		if ($USER->id == $admin->id) {
			$isadmin = true;
			break;
		}
	}

	if (($uid == $USER->id) || ($isadmin == true)) echo '<img width="12" align="left" style="border:none;" src="images/trash.png" class="shakeimage" onMouseover="init(this);rattleimage()" onMouseout="stoprattle(this);top.focus()" onClick="top.focus()">';
	else echo "";
?>
</a>
</div>
<?php
	if ($Journal === 1) {

		// Is the current user a Teacher? If YES, then allow him or her to view the comment!
		// You see, the role-id value of 3 means teacher!

		if (user_has_role_assignment($USER->id, 3)) $isaTeacher = true;
		else $isaTeacher = false;

		// Was the comment was posted by a teacher? If yes, then show the comment! You see, role-id value of 3 means teacher!

		if (user_has_role_assignment($uid, 3)) $postedByTeacher = true;
		else $postedByTeacher = false;
		$allowed = false;
		$moodleAdminSql = "SELECT name 
FROM  `" . $CFG->prefix . "config` 
WHERE  `name` LIKE  'siteadmins'
AND value LIKE  '%$USER->id%'";
		$isa = mysql_query($moodleAdminSql);
		$isad = mysql_num_rows($isa);
		if ($isad > 0) {
			$allowed = true;
		}
		else {
			$ism = mysql_query($courseSql);
			$ismem = mysql_num_rows($ism);
			if ($ismem > 0) {
				$allowed = true;
			}
		}

		// echo 'allowed is ['.$allowed.']';

		if (($allowed === true) || ($isaTeacher === true) || ($postedByTeacher === true)) {
			echo '<a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a>' . ' ' . $comment;
		}
		else {

			// echo "Not admin!";
			// Journal mode was set to 1. Therefore Wall posts are revealed only selectively.
			// Check to see if the userid of the post's owner is the same as the current logged in
			// user himself/herself, [OR] if the userid of the post's owner is the
			// Teacher or Course Administrator. If it is true in EITHER case, then we should
			// reveal the post to the logged in user.

			$admins = get_admins();
			$postedByAdmin = false;
			foreach($admins as $admin) {
				if ($uid == $admin->id) {
					$postedByAdmin = true;
					break;
				}
			}

			// echo $postedByAdmin;

			if (($postedByAdmin === true) || ($uid === $USER->id))
			/* Show posts written by the own user and by the Admin user */
			echo '<a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a>' . ' ' . $comment;
			else {
				/* Hide the post that is written by someone else who is  non-admin user! */
				echo '<a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a>' . ' <i><font color="red">comment hidden</font></i>';
			}
		}
	}
	else {

		// Journal mode was not set to 1. Therefore everything is revealed to all users.

		echo '<a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a>' . ' ' . $comment;
	}

?>

<div class="stcommenttime">
<?php
	time_stamp($time); ?>



</div> 


</div>
</div>
<?php
}

?>
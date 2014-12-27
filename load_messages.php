<script type="text/javascript">
function setSelectionRange(input, selectionStart, selectionEnd) {
  if (input.setSelectionRange) {
    input.focus();
    input.setSelectionRange(selectionStart, selectionEnd);
  }
  else if (input.createTextRange) {
    var range = input.createTextRange();
    range.collapse(true);
    range.moveEnd('character', selectionEnd);
    range.moveStart('character', selectionStart);
    range.select();
  }
}

function replaceSelection (input, replaceString) {
	if (input.setSelectionRange) {
		var selectionStart = input.selectionStart;
		var selectionEnd = input.selectionEnd;
		input.value = input.value.substring(0, selectionStart)+ replaceString + input.value.substring(selectionEnd);
    
		if (selectionStart != selectionEnd){ 
			setSelectionRange(input, selectionStart, selectionStart + 	replaceString.length);
		}else{
			setSelectionRange(input, selectionStart + replaceString.length, selectionStart + replaceString.length);
		}

	}else if (document.selection) {
		var range = document.selection.createRange();

		if (range.parentElement() == input) {
			var isCollapsed = range.text == '';
			range.text = replaceString;

			 if (!isCollapsed)  {
				range.moveStart('character', -replaceString.length);
				range.select();
			}
		}
	}
}



// We are going to catch the TAB key so that we can use it, Hooray!

function catchTab(item,e){
	if(navigator.userAgent.match("Gecko")){
		c=e.which;
	}else{
		c=e.keyCode;
	}
	if(c==9){
		replaceSelection(item,String.fromCharCode(9));
		setTimeout("document.getElementById('"+item.id+"').focus();",0);	
		return false;
	}
		    
}
</script>

<?php

// Wall_resource
// Srinivas Tamada http://9lessons.info
// Loading Comments link with load_updates.php

include_once 'includes/make_img_tag.php';

foreach((array)$updatesarray as $data) {
	$msg_id = $data['msg_id'];
	$orimessage = "";
	$token = explode(":mode:", $data['message']);
  
    		
	if(!empty($token[1]))
	   $textOnly = (int)$token[1]; //Media format
	else
	   $textOnly = 0;  //Plain text format
	
	$message = $token[0];
	$message = str_replace("&acirc;€”", "-", $message);
	$message = str_replace("&acirc;€“", "-", $message);
	$message = str_replace("&acirc;€™", "'", $message);
	$message = str_replace("&acirc;€œ", "'", $message);
	$message = str_replace("&acirc;€", "'", $message);
	$message = str_replace("&", "kxk", $message);
	$message = str_replace('  ', ' &nbsp;', $message);
	$message = str_replace('	', '&nbsp;&nbsp;&nbsp;&nbsp;', $message);
	$message = str_replace("\t", '&nbsp;&nbsp;&nbsp;', $message);
	$id = $data['activity_id'];
	/* Start of complex block */
	if (!$textOnly) {
		if (strpos($message, 'http://youtu.be/') === false);
		else $message = str_replace("youtu.be/", "www.youtube.com/watch?v=", $message);
		$findme1 = 'https://www.youtube.com/watch?v=';
		$pos1 = strpos($message, $findme1);
		$findme2 = 'http://www.youtube.com/watch?v=';
		$pos2 = strpos($message, $findme2);
		if (($pos1 === false) && ($pos2 === false)) {

			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.
			// if ($pos === false) {

			$findme = 'www.ted.com/talks';
			$pos2 = strpos($message, $findme);
			if ($pos2 == false) {

				// look for a jpg, gif, or png reference
				// if not found, carry on as normal

				$pos = stripos($message, ".jpg") || $pos = stripos($message, ".png") || $pos = stripos($message, ".gif");
				if ($pos === false) {
					$findme = 'https://voicethread.com/share/';
					$pos = strpos($message, $findme);
					if ($pos === false) {
						$findme = 'http://teachertube.com/viewVideo.php?video_id=';
						$pos = strpos($message, $findme);
						if ($pos === false) {
							$findme = 'http://www.slideshare.net';
							$pos = strpos($message, $findme);
							if ($pos === false) {
								$findme = 'http://www.scribd.com/doc/';
								$pos = strpos($message, $findme);
								if ($pos === false) {
									$findme = 'http://vimeo.com';
									$pos = strpos($message, $findme);
									if ($pos === false) {
										$findme = 'http://www.chatbotmaker.com/videofiles';
										$findme2 = 'http://www.labodanglais.com/videofiles';
										$pos = strpos($message, $findme);
										$pos2 = strpos($message, $findme2);
										if (($pos === false) && ($pos2 === false)) {
											$findme = 'http://www.chatbotmaker.com/voicefiles';
											$findme2 = 'http://www.labodanglais.com/voicefiles';
											$pos = strpos($message, $findme);
											$pos2 = strpos($message, $findme2);
											if (($pos === false) && ($pos2 === false)) {
												$message = tolink(htmlentities(nl2br($message)));
											}
											else {
												$oriMesg = $message;
												$exp = explode("http://", $message);
												$audiobot = $exp[1];
												$code = '<object data="' . 'http://' . $audiobot . '" height="680" width="500"></object></div>';

												// we replace each {id} with the actual ID of the video to get embed code for this particular video

												$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;

												// $message = "<p>".$code;

											}
										}
										else { //http://www.chatbotmaker.com/videofiles/07/mp4/index.html
											$oriMesg = $message;
											$exp = explode("http://", $message);
											$chatbotmaker = str_replace("index.html", "index_right_frame_only.html", $exp[1]);
											$code = '<object data="' . 'http://' . $chatbotmaker . '" height="700" width="500"></object>';

											// we replace each {id} with the actual ID of the video to get embed code for this particular video

											$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;
										}
									}
									else {
										$oriMesg = $message;
										$message = str_replace("vimeo.com/", "www.youtube.com/watch?v=", $message);
										$arr = explode("https://www.youtube.com", $message, 2);
										$first = $arr[0];
										$url = $message;
										$codeid = 0;

										// we get the unique video id from the url by matching the pattern

										preg_match("/v=([^&]+)/i", $url, $matches);
										if (isset($matches[1])) $codeid = $matches[1];
										if (!$id) {
											$matches = explode('/', $url);
											$codeid = $matches[count($matches) - 1];
										}

										// this is your template for generating embed codes

										$code = '<iframe src="http://player.vimeo.com/video/{id}" width="400" height="233" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

										// we replace each {id} with the actual ID of the video to get embed code for this particular video

										$code = str_replace('{id}', $codeid, $code);
										$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;
									}
								}
								else {
									$oriMesg = $message;
									$message = str_replace("http://www.scribd.com/doc/", "www.youtube.com/watch?v=", $message);
									$arr = explode("https://www.youtube.com", $message, 2);
									$first = $arr[0];
									$url = $message;
									$codeid = 0;

									// we get the unique video id from the url by matching the pattern

									preg_match("/v=([^&]+)/i", $url, $matches);
									if (isset($matches[1])) $codeid = $matches[1];
									if (!$codeid) {
										$matches = explode('/', $url);
										$codeid = $matches[count($matches) - 1];
									}

									// this is your template for generating embed codes

									$code = '<a href="http://www.scribd.com/doc/{id}" style="margin: 12px auto 6px auto; font-family: Helvetica,Arial,Sans-serif; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none; display: block; text-decoration: underline;"></a><iframe class="scribd_iframe_embed" src="http://www.scribd.com/embeds/{id}/content?start_page=1&view_mode=scroll" data-auto-height="true" data-aspect-ratio="" scrolling="no" width="100%" height="600" frameborder="0"></iframe>';

									// we replace each {id} with the actual ID of the video to get embed code for this particular video

									$code = str_replace('{id}', $codeid, $code);
									$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;
								}
							}
							else {
								$oriMesg = $message;
								$message = str_replace("http://www.slideshare.net/", "www.youtube.com/watch?v=", $message);
								$arr = explode("https://www.youtube.com", $message, 2);
								$first = $arr[0];
								$url = $message;
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
								$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;
							}
						}
						else {
							/* Embed a TeacherTube video! */
							$oriMesg = $message;
							$message = str_replace("teachertube.com/viewVideo.php?video_id=", "www.youtube.com/watch?v=", $message);
							$arr = explode("https://www.youtube.com", $message, 2);
							$first = $arr[0];
							$url = $message;
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
							$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;
						}
					}
					else {
						/* Embed a Voicethread! */
						$orimesg = $message;
						/* Voicethread! */

						// Check if the final character of the URL is a frontslash. If yes, nuke it!

						if (substr($message, -1) == '/') {
							$message = rtrim($message, '/');

							// $message = preg_replace('{/$}', '', $message);

						}

						$message = str_replace("voicethread.com/share/", "www.youtube.com/watch?v=", $message);
						$arr = explode("https://www.youtube.com", $message, 2);
						$first = $arr[0];
						$url = $message;
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
						$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;
					}
				}
				else {
					/* Embed an image! */
					/* Embed an image! */
					$imgsrc = "";
					
					$beg = stripos($message, "http://");
					if ($beg === false) $beg = stripos($message, "www.");
					if ($beg === false) $beg = stripos($message, "ftp.");
					/* This part is to see if the user used the Nicedit GUI's "insert picture" tool to input a URL of an image. */
					$checker = stripos($message, "img src");
					if ($checker !== false) {
						$message = nl2br($message);
					}
					else {
						if ($beg !== false) {
							$NewLineExists = strpos($message, "<div>");
							if ($NewLineExists !== false) {
								$message = preg_replace('/<[^>]*>/', ' ', $message);
							}

							$imgsrc = '$beg=' . $beg;
							$end = stripos($message, ".jpg");
							if ($end === false) $end = stripos($message, ".gif");
							if ($end === false) $end = stripos($message, ".png");
							if ($end !== false) {
								$imgsrc = substr($message, $beg, $end - $beg + 4);
							}
						}

						$width = 400;
						$target = " onclick=\"window.open(this.href,\\'_blank\\');return false;\"";
						$message = preg_replace("#(^|[\n \]])([\w]+?://[\w\#$%&~/.\-;:=,?@+]*)#ise", "'\\1<insertimage><P><center><a href=\'" . $imgsrc . "\'" . $target . "><img src=\'\\2\' width=\'" . $width . "\'></img></a></center></insertimage>'", $message);
						$message = nl2br($message);
					}
				}
			}
			else {
				/* Embed a TED.com video! */
				$oriMesg = $message;
				$httpsExists = strpos($message, "https");
				if ($httpsExists !== false) $arr = explode("https://www.ted.com/talks/", $message, 2);
				else $arr = explode("http://www.ted.com/talks/", $message, 2);
				$first = $arr[0];
				$second = $arr[1];
				$third = "";
				$NewLineExists = strpos($second, "<div>");
				if ($NewLineExists !== false) {
					$first = str_replace("<div>", "<br />", $first);
					$second = str_replace("<div>", "<br />", $second);
					$second = str_replace("</div>", "", $second);
					$nexthalf = explode("<br />", $second, 2);
					$second = $nexthalf[0];
					$third = $nexthalf[1];
				}
				else {
					$SpaceExists = strpos($second, " ");
					if ($SpaceExists !== false) {
						$nexthalf = explode(' ', $second, 2);
						$second = $nexthalf[0];
						$third = $nexthalf[1];
					}
				}

				$TED_talk_name = $second;
				$TED_trailing_text = $third;
				$url = $message;
				$urlid = basename($url);
				$code = $first . '<p>' . '<div id="img_wrapper">
			<iframe src="https://embed-ssl.ted.com/talks/' . $TED_talk_name . '.html" width="440" height="248" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			</div>' . '<br />' . $TED_trailing_text;
				$message = $code;
			}
		}
		else {
			/* Embed a Youtube video! */
			$httpExists = strpos($message, "http:");
			if ($httpExists !== false) $message = str_replace("http:", "https:", $message);
			$oriMesg = $message;
			$arr = explode("https://www.youtube.com/watch?v=", $message, 2);
			$first = $arr[0];
			$second = $arr[1];
			$third = "";
			$NewLineExists = strpos($second, "<div>");
			if ($NewLineExists !== false) {
				$first = str_replace("<div>", "<br />", $first);
				$second = str_replace("<div>", "<br />", $second);
				$second = str_replace("</div>", "", $second);
				$nexthalf = explode("<br />", $second, 2);
				$second = $nexthalf[0];
				$third = $nexthalf[1];
			}
			else {
				$SpaceExists = strpos($second, " ");
				if ($SpaceExists !== false) {
					$nexthalf = explode(' ', $second, 2);
					$second = $nexthalf[0];
					$third = $nexthalf[1];
				}
			}

			$Youtube_id = $second;
			$Youtube_trailing_text = $third;
			$url = $message;
			$codeid = 0;

			// we get the unique video id from the url by matching the pattern

			preg_match("/v=([^&]+)/i", $url, $matches);
			if (isset($matches[1])) $codeid = $matches[1];
			if (!$codeid) {
				$matches = explode('/', $url);
				$codeid = $matches[count($matches) - 1];
			}

			$code = $first . '<p>' . '<div id="img_wrapper">
			<iframe class="youtube-player" type="text/html" width="440" height="248" src="https://www.youtube.com/embed/' . $Youtube_id . '" frameborder="0">
			</iframe></div>' . '<p>' . $Youtube_trailing_text;
			$code = str_replace('{id}', $codeid, $code);
			$code = str_replace("<div>", "<br />", $code);
			$code = str_replace("</div>", "", $code);

			// $code = "";
			// $message=$first."<p>".$code;
			// $message=tolink(htmlentities(nl2br($oriMesg)))."<p>".$code;

			$message = $code;
		}

		/* } */
	}

	/* End of complex block */
	$time = $data['created'];
	$username = $data['firstname'] . ' ' . $data['lastname'];
	$uid = $data['uid_fk'];

	// $face=$Wall->Gravatar($uid);
	// $face="http://www.moodurian.com/user/pix.php/".$uid."/f2.jpg";

	$commentsarray = $Wall->Comments($msg_id, $uid);
?>

<script type="text/javascript"> 
$(document).ready(function(){$("#stexpand<?php
	echo $msg_id; ?>").oembed("<?php
	echo $message; ?>",{maxWidth: 400, maxHeight: 300});});
</script>
<div class="stbody" id="stbody<?php
	echo $msg_id; ?>">

    
	  
<div class="stimg">

	   
<?php
	$ustring = "select * from mdl_user where id = " . $uid;
	$user = $DB->get_record_sql($ustring);
	$cface = $OUTPUT->user_picture($user, array(
		'size' => 50
	));
	echo $cface;

	// echo '<img class="grayscale" src="'.$cface.'"/>';

?>
</div> 
<div class="sttext">
<a class="stdelete" href="#" id="<?php
	echo $msg_id; ?>" title="Delete update">
<?php
	$admins = get_admins();
	$isadmin = false;
	foreach($admins as $admin) {
		if ($USER->id == $admin->id) {
			$isadmin = true;
			break;
		}
	}

	if (($uid == $USER->id) || ($isadmin == true)) echo '<img width="14" style="border:none;" src="images/trash.png" class="shakeimage" onMouseover="init(this);top.focus()">';
	else echo "";
?></a>

<?php
	$message = str_replace("xzx", "&", $message);
	$message = str_replace('qxq', '+', $message);
	$message = str_replace('&gt;', '>', $message);
	$message = str_replace('&lt;', '<', $message);
	$message = str_replace('&quot;', '"', $message);
	$message = str_replace('&Acirc;', '&nbsp;', $message);
	$message = str_replace('wzw', '&amp;', $message);
	$message = str_replace('&amp;amp;', '&amp;', $message);
	if (!$textOnly) {
		if (strlen($message) == strlen(strip_tags($message))) {

			// does not contains HTML

			$message = tolink(htmlentities(nl2br($message)));
		}
	}
	else {
		$message = str_replace('&Acirc;', '&nbsp;', $message);
		$message = str_replace('@@@@', '&', $message);
		$message = tolink(htmlentities(nl2br($message)));
	}

	/*
	How the Journal version of the wall works:
	If the current user is not an admin user, and the Admin user has set the Wall as a journal wall,
	then the current user (who is a student) is not allowed to see/view the other users' posts
	*/
	if ($Journal === 1) {

		// If Current user is a teacher, show the post. You see, role-id value of 3 means teacher!

		if (user_has_role_assignment($USER->id, 3)) $isaTeacher = true;
		else $isaTeacher = false;
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

		if (($allowed === true) || ($isaTeacher === true)) {
			echo '<b><a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a></b>' . ' ' . $message;
		}
		else {

			// Journal mode was set to 1. Therefore Wall posts are revealed only selectively.
			// Is the current user a Teacher? If yes, then reveal all to him or her!

			if (user_has_role_assignment($USER->id, 3)) //role-id value of 3 means teacher!
			$isaTeacher = true;
			else $isaTeacher = false;
			if (user_has_role_assignment($uid, 3)) //Post was done by a teacher. You see, role-id value of 3 means teacher!
			$postedByATeacher = true;
			else $postedByATeacher = false;

			// Check to see if the userid of the post's owner is the same as the current logged in
			// user himself/herself, [OR] if the userid of the post's owner is the
			// Teacher or Course Administrator. If it is true in EITHER case, then we should
			// reveal the post to the logged in user.

			$admins = get_admins();
			$showPost = false;
			foreach($admins as $admin) {
				if ($uid == $admin->id) {
					$showPost = true;
					break;
				}
			}

			$fishfish = false;
			if (($uid === $USER->id) || ($showPost === true) || ($isaTeacher === true) || ($postedByATeacher === true))
			/* Show posts written by the own user and by the Admin user and by Course Teacher*/
			echo '<b><a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a></b>' . ' ' . $message;
			else {
				/* Hide the post that is written by someone else who is  non-admin user and a non-teacher user! */
				echo '<b><a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a></b><i><font color="red">' . ' post hidden.</font></i>';
				$fishfish = true;
			}
		}
	}
	else {

		// Journal mode was not set to 1. Therefore everything is revealed to all users.

		echo '<b><a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a></b>' . ' ' . $message;
	}

	// Ratings

	$content = 'content';
	mysql_query("set character_set_results='utf8'");
	$q = mysql_query("SELECT * FROM $content WHERE id='$id'"); //GETS THE CONTENT ID
	$r = mysql_fetch_assoc($q);
?>


<script>
function rate(rating){ //'rating' VARIABLE FROM THE FORM in view.php
var data = 'rating='+rating+'&id=<?php
	echo $msg_id; ?>&user=<?php
	echo $uid; ?>';
var ratingID = '<?php
	echo $msg_id; ?>';
$.ajax({
type: 'POST',
url: 'rate.php', //POSTS FORM TO THIS FILE
data: data,
success: function(e){
var rvalue=rating.split(" ");
$("#ratings"+rvalue[1]).html(e); //REPLACES THE TEXT OF view.php
}
});
}
</script>

<style> <!-- /*GIVES THE BUTTONS AND CANCEL LINK THE POINTER ON MOUSEOVER*/ -->
#like, #dislike, #cancel {
cursor: pointer;
}
<!--GIVES THE CANCEL BUTTON AN UNDERLINE ON MOUSEOVER*/-->
#cancel:hover {
text-decoration: underline;
}
</style>
<?php
	if ($Likes) {

		// IF $id EXISTS, THEN COUNT LIKES & DISLIKES

		if ($msg_id) {

			// COUNTS THE TOTAL NUMBER OF LIKES &amp; DISLIKES

			mysql_query("set character_set_results='utf8'");
			$q = mysql_query("SELECT * FROM $ratings WHERE id='$msg_id' AND rating='like'");
			$likes = mysql_num_rows($q);
			mysql_query("set character_set_results='utf8'");
			$q = mysql_query("SELECT * FROM $ratings WHERE id='$msg_id' AND rating='dislike'");
			$dislikes = mysql_num_rows($q);

			// LIKE & DISLIKE IMAGES

			$l = 'images/like.png';
			$d = 'images/dislike.jpg';

			// CHECKS IF USER HAS ALREADY RATED CONTENT

			mysql_query("set character_set_results='utf8'");
			$q = mysql_query("SELECT * FROM $ratings WHERE id='$msg_id' AND ip='$ip'");
			$r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM

			// IF SO, THE RATING WILL HAVE A SHADOW

			if ($r["rating"] == "like") {
				$l = 'images/like_shadow.png';
			}

			if ($r["rating"] == "dislike") {
				$d = 'images/dislike_shadow.jpg';
			}

			$m = '<div><img class="shakeimage" onMouseover="init(this);rattleimage()" ' . 'onMouseout="stoprattle(this);top.focus()" style="width:16; ' . 'height:16;vertical-align: middle;" id="like ' . $msg_id . '" ' . 'onClick="rate($(this).attr(\'id\'))" width="16" src="' . $l . '">' . '<span><font="Tahoma"><b>&nbsp;</b>' . (($likes > 1) ? $likes . " likes" : (($likes == 1) ? $likes . " like" : "")) . '</font></span></div>';

			// EVERYTHING HERE DISPLAYED IN HTML
			
			if (user_has_role_assignment($USER->id, 3)) //role-id value of 3 means teacher!
			   $isaTeacher = true;
			else 
			   $isaTeacher = false;
			   
			if (user_has_role_assignment($uid, 3)) //Post was done by a teacher. You see, role-id value of 3 means teacher!
				$postedByATeacher = true;
			else 
				$postedByATeacher = false;

			
			$fishfish = false;
			
			$admins = get_admins();
			$showPost = false;
			foreach($admins as $admin) {
				if ($uid == $admin->id) {
					$showPost = true;
					break;
				}
			}
			
			if (($uid === $USER->id) || ($showPost === true) || ($isaTeacher === true) || ($postedByATeacher === true))
				;
			else {
				$fishfish = true;
			}
			
			if ($fishfish !== true) echo /*$con.*/
			'<br /><div id="ratings' . $msg_id . '">' . $m . '</div>';
		}
		else {
			mysql_query("set character_set_results='utf8'");
			mysql_query("INSERT INTO ratings VALUES('like','$msg_id','$ip', '$uid')") or die(mysql_error()); //INSERTS INITIAL RATING

			// COUNTS THE TOTAL NUMBER OF LIKES &amp; DISLIKES

			mysql_query("set character_set_results='utf8'");
			$q = mysql_query("SELECT * FROM $ratings WHERE id='$msg_id' AND rating='like'");
			$likes = mysql_num_rows($q);
			mysql_query("set character_set_results='utf8'");
			$q = mysql_query("SELECT * FROM $ratings WHERE id='$msg_id' AND rating='dislike'");
			$dislikes = mysql_num_rows($q);

			// LIKE & DISLIKE IMAGES

			$l = 'images/like.png';
			$d = 'images/dislike.jpg';

			// CHECKS IF USER HAS ALREADY RATED CONTENT

			mysql_query("set character_set_results='utf8'");
			$q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND ip='$ip'");
			$r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM

			// IF SO, THE RATING WILL HAVE A SHADOW

			if ($r["rating"] == "like") {
				$l = 'images/like_shadow.png';
			}

			if ($r["rating"] == "dislike") {
				$d = 'images/dislike_shadow.jpg';
			}

			$m = '<div><img class="shakeimage" onMouseover="init(this);rattleimage()" ' . 'onMouseout="stoprattle(this);top.focus()" style="width:16; ' . 'height:16;vertical-align: middle;" id="like ' . $msg_id . '" ' . 'onClick="rate($(this).attr(\'id\'))" width="16" src="' . $l . '">' . '<span><font="Tahoma"><b>&nbsp;</b>' . (($likes > 1) ? $likes . " likes" : (($likes == 1) ? $likes . " like" : "")) . '</font></span></div>';

			// EVERYTHING HERE DISPLAYED IN HTML

			if ($fishfish !== true) echo /*$con.*/
			'<br /><div id="ratings' . $msg_id . '">' . $m . '</div>';
		}
	}

?>

<div class="sttime"><?php

			$fishfish = false;
			if (($uid === $USER->id) || ($showPost === true) || ($isaTeacher === true) || ($postedByATeacher === true))
				;
			else {
				$fishfish = true;
			}


	time_stamp($time); ?> <a href='#' class='commentopen' id='<?php
	echo $msg_id; ?>' title='Comment'><?php
	if ($fishfish !== true) echo 'Comment';
	else echo ''; ?> </a></div> 

<div id="stexpandbox">
<div id="stexpand<?php
	echo $msg_id; ?>"></div>
</div>

<div class="commentcontainer" id="commentload<?php
	echo $msg_id; ?>">

<?php
	if ($Journal === 1) {

		// Journal mode was set to 1. Therefore Wall posts are revealed only selectively.
		// Is the current user a Teacher? If yes, then reveal all to him or her!
		// if (user_has_role_assignment($USER->id, 3))  //role-id value of 3 means teacher!
		// $isaTeacher = true;
		// Check to see if the userid of the post's owner is the same as the current logged in
		// user himself/herself, [OR] if the userid of the post's owner is the
		// Teacher or Course Administrator. If it is true in EITHER case, then we should
		// reveal the post to the logged in user.
		// Is the current user a site admin? If yes, then reveal all to him or her!

		$admins = get_admins();
		$postedByAdmin = 0;
		foreach($admins as $admin) {
			if ($uid == $admin->id) {
				$postedByAdmin = 1;
				break;
			}
		}

		if (($postedByAdmin) || ($uid === $USER->id) || ($isaTeacher === true))
		/* Show comments written by the own user and by the Admin user */
		include ('load_comments.php');

		else {
			/* Hide the post that is written by someone else who is  non-admin user!
			So do nothing, and do not load the comments. I.e., don't load the load_comments.php file contents! */
			include ('load_comments.php');

		}
	}
	else {

		// Journal mode was not set to 1. Therefore everything is revealed to all users.

		include ('load_comments.php');

	}

?>	

</div>
<div class="commentupdate" style='display:none' id='commentbox<?php
	echo $msg_id; ?>'>
<div class="stcommentimg">
<?php
	$ustring = "select * from mdl_user where id = " . $USER->id;
	$user = $DB->get_record_sql($ustring);
	$cface = $OUTPUT->user_picture($user, array(
		'size' => 36
	));
	echo $cface;
?>

</div> 
<div class="stcommenttext" >



<script>
	$(function(){		
	 $('textarea').autosize({append: "\n"});
   });
</script>

<form method="post" action="">
<textarea name="comment" class="comment" placeholder="Give a comment." maxlength="15000"  id="ctextarea<?php
	echo $msg_id; ?>" wrap="off" onkeydown="return catchTab(this,event)"></textarea>

<br />
<input type="submit"  value=" Comment "  id="<?php
	echo $msg_id; ?>" class="comment_button"/>
</form>


</div>
</div>


</div> 

</div>
<?php
}

?>
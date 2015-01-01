<?php
/*

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//
// Load latest comment

*/
error_reporting(0);
include_once 'includes/db.php';

include_once 'includes/functions.php';

include_once 'includes/tolink.php';

include_once 'includes/time_stamp.php';

include_once 'session.php';

$wcid = (int)$_GET['CID'];

// echo "Comment:wcid is ".$wcid;

$id = (int)$_GET['Id'];
$Wall = new Wall_Updates($wcid);

if (isSet($_POST['comment'])) {

	// $comment=$_POST['comment'];

	$comment = addslashes($_POST['comment']);
	$comment = str_replace("qxq", "+", $comment);
	$msg_id = $_POST['msg_id'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$cdata = $Wall->Insert_Comment($wcid, $uid, $msg_id, $id, $comment);
	if ($cdata) {
		$com_id = $cdata['com_id'];
		$comment = str_replace("&acirc;Äî", "-", $comment);
		$comment = str_replace("&acirc;Äì", "-", $comment);
		$comment = str_replace("&acirc;Äô", "'", $comment);
		$comment = str_replace("&acirc;Äú", "'", $comment);
		$comment = str_replace("&acirc;Äù", "'", $comment);
		$comment = str_replace("&", "kxk", $comment);
		$comment = str_replace('  ', ' &nbsp;', $comment);
		$comment = str_replace('	', '&nbsp;&nbsp;&nbsp;&nbsp;', $comment);
		$comment = str_replace("\t", '&nbsp;&nbsp;&nbsp;', $comment);
		if (strpos($comment, 'https://youtu.be/') === false);
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

		// Frankie-end

		$time = $cdata['created'];

		// $username=$cdata['username'];

		$username = $USER->firstname . ' ' . $USER->lastname;
		$uid = $cdata['uid_fk'];


?>
 
 <?php
		$comment = str_replace("kxk", "&", $comment);
		$comment = str_replace('qxq', '+', $comment);
		$comment = str_replace('&gt;', '>', $comment);
		$comment = str_replace('&lt;', '<', $comment);
		$comment = str_replace('&quot;', '"', $comment);
		$comment = str_replace('&Acirc;', '&nbsp;', $comment);
		$comment = str_replace('wzw', '&amp;', $comment);
		$comment = str_replace('&amp;amp;', '&amp;', $comment);
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
<a class="stcommentdelete" href="#" id='<?php
		echo $com_id; ?>'><img width="12" style="border:none;" src="images/trash.png" class="shakeimage" onMouseover="init(this);rattleimage()" onMouseout="stoprattle(this);top.focus()" onClick="top.focus()"></a>
<?php
		echo '<a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a>' . ' ' . stripslashes($comment); ?> 
<div class="stcommenttime"><?php
		time_stamp($time); ?></div> 
</div>
</div>
<?php
	}
}

?>
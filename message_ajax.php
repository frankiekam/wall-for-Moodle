<!--
/* Modif

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info 
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//

*/
-->

<?php
error_reporting(0);
include_once 'includes/make_img_tag.php';

include_once 'includes/db.php';

include_once 'includes/functions.php';

include_once 'includes/tolink.php';

include_once 'includes/time_stamp.php';

include_once 'session.php';


$wcid = (int)$_GET['CID'];
$Wall = new Wall_Updates($wcid);
$id = (int)$_GET['Id'];
$Email = !isset($_GET["Email"]) ? 0 : (int)$_GET["Email"];
$Likes = !isset($_GET["Likes"]) ? 0 : (int)$_GET["Likes"];
$AdminOnly = !isset($_GET['AdminOnly']) ? 0 : (int)$_GET['AdminOnly'];
$WordLimit = !isset($_GET['WordLimit']) ? 0 : (int)$_GET['WordLimit'];
$Journal = !isset($_GET["Journal"]) ? 0 : (int)$_GET["Journal"];
$Order = !isset($_GET["Order"]) ? 0 : (int)$_GET["Order"];
$WordStats = !isset($_GET["WordStats"]) ? 0 : (int) $_GET["WordStats"];
$MinWords = !isset($_GET["MinWords"]) ? 0 : (int) $_GET["MinWords"];
$MinSentences = !isset($_GET["MinSentences"]) ? 0 : (int) $_GET["MinSentences"];
$Desc = !isset($_GET["Desc"]) ? "The Wall" : $_GET["Desc"];

//echo '$WordStats from message is '.$WordStats;

if (isSet($_POST['update'])) {
	$update = addslashes($_POST['update']);
	$update = str_replace("qxq", "+", $update);
	$update = str_replace("kxk", "&", $update);
	$username = $USER->firstname . ' ' . $USER->lastname;

$token = explode(":mode:",$update);	
//$WordStats = 1;

if($WordStats)
 {
    $sentences = preg_split('/(?<=[.?!;:])\s+/', $token[0], -1, PREG_SPLIT_NO_EMPTY);
    $wordCount = adv_count_words($token[0]);
	$sentenceCount = count($sentences);
	
	
	//echo '$sentenceCount is '.$sentenceCount.'. '.'$wordCount is '.$wordCount.'. ';
	
	$text1 = ($wordCount==1)?' word':' words';
	$text2 = ($sentenceCount==1)?' sentence':' sentences';
	$avewordpersentence = (float)$wordCount/$sentenceCount;
	$text3 = ($avewordpersentence==1)?' w':' w';
	$value = ($avewordpersentence == ((int) $avewordpersentence))?((int)$avewordpersentence) : number_format($avewordpersentence, 1, '.','');

	 if($wordCount >= $MinWords)
       $StringWordColor = "green";
	else
	   $StringWordColor = "red"; 	

    if($sentenceCount >= $MinSentences)
	   $StringSentColor = "green"; 
	else
	   $StringSentColor = "red"; 	

	$statistics = new TextStatistics;
    $updateText = $update;
	$Flesch_Score = $statistics->flesch_kincaid_reading_ease($updateText);
	
	$statsText = '<br><font size="-1">Stats: </font><font size="-1" color="'.$StringWordColor.'">'.$wordCount.$text1.'</font>'.",".'<font size="-1" color="'.$StringSentColor.'"> '.$sentenceCount.$text2.'</font><font size="-1" color="darkblue">, '.$value.$text3.'ps. </font>';
	
	
	
   $n = $Flesch_Score;
  
   /* Assign Readability badges based on Flesch Kincaid Score
   see: http://www.mobilefish.com/services/readability_tester/readability_tester.php
   80-100 : Very Easy 
   70-89 : Easy  
   50-69 : Standard 
   20-49 : Difficult 
   0-19  : Very Difficult 
   */ 
  
   if($n >= 80) 
	$statsText = $statsText.' <img height="18" src="images/excellent.png" title="The text is very easy to read (score is '.$n.')">';
   else if($n >= 70) 
			$statsText = $statsText.'<img height="18" src="images/good.png" title="Text is easy to read (score is '.$n.')">';
		else if($n >= 50) 
				$statsText = $statsText.'<img height="18" src="images/standard.png" title="Text has a standard readability (Score is '.$n.')">';
			else if($n >= 20) 
					$statsText = $statsText.'<img height="18" src="images/poor.png" title="Text is difficult to read (score is '.$n.')">';
				else 
					$statsText = $statsText.'<img height="18" src="images/verypoor.png" title="Text is very difficult to read (score is '.$n.')">';
  
	 if($wordCount >= $MinWords)
		$statsText = $statsText.' <img height="18" src="images/good_word.png" title="Met the target of '.$MinWords.' words">';
     /*else
		$statsText = $statsText.' <img height="18" src="images/poor_words.png" title="Met the target of '.$MinWords.' words">';*/
	 
	 if($sentenceCount >= $MinSentences)
		$statsText = $statsText.' <img height="18" src="images/good_sentence.png" title="Met the target of '.$MinSentences.' sentences">';
     /*else
		$statsText = $statsText.' <img height="18" src="images/poor_sentences.png" title="Met the target of '.$MinSentences.' sentences">'; */

	 
		/* Add the statistics to the message! */
		$token = explode(":mode:",$update);	
        $update = $token[0].$statsText.":mode:".$token[1];
		
}
		
	$data = $Wall->Insert_Update($wcid, $uid, $id, $update);


	if ($data) {
		$msg_id = $data['msg_id'];
		$token = explode(":mode:", $data['message']);
		
		if(!empty($token[1]))
		   $textOnly = (int)$token[1]; //Media format
		else
		   $textOnly = 0;  //Plain text format

		//echo '$textOnly is '.$textOnly;
		   
		$message = $token[0];
		$message = str_replace("&acirc;Äî", "-", $message);
		$message = str_replace("&acirc;Äì", "-", $message);
		$message = str_replace("&acirc;Äô", "'", $message);
		$message = str_replace("&acirc;Äú", "'", $message);
		$message = str_replace("&acirc;Äù", "'", $message);
		$message = str_replace("&", "kxk", $message);
		$message = str_replace("+", "qxq", $message);
		$message = str_replace('  ', ' &nbsp;', $message);
		$message = str_replace('	', '&nbsp;&nbsp;&nbsp;&nbsp;', $message);
		$message = str_replace("\t", '&nbsp;&nbsp;&nbsp;', $message);
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
				$pos = strpos($message, $findme);
				if ($pos === false) {

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

													// $message=tolink(htmlentities(nl2br($data['message'])));

													$message = tolink(htmlentities(nl2br($message)));
												}
												else {
													$oriMesg = $message;
													$exp = explode("http://", $message);
													$audiobot = $exp[1];
													$code = '<object data="' . 'http://' . $audiobot . '" height="680" width="500"></object></div>';

													// we replace each {id} with the actual ID of the video to get embed code for this particular video

													$message = tolink(htmlentities(nl2br($oriMesg))) . "<p>" . $code;
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
						else { /* This means the user didn't use the Nicedit's Insert image tool! */
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
					<iframe src="https://embed-ssl.ted.com/talks/' . $TED_talk_name . '.html" width="440" height="220" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
					</div>' . '<br />' . $TED_trailing_text;
					$message = $code;
				}
			}
			else {
				/* Embed a Youtube video! */
				$httpExists = strpos($message, "http:");
				if ($httpExists !== false) $message = str_replace("http:", "https:", $message);
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
				$message = $code;
			}

			/* } */
		}
		
		
		
		/* End of complex block */
		$time = $data['created'];
		$uid = $data['uid_fk'];
		$username = $USER->firstname . ' ' . $USER->lastname;

		// $face=$Wall->Gravatar($uid);

		$commentsarray = $Wall->Comments($msg_id, $uid, $COURSE->id);
?>
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
?>
</div> 
<div class="sttext">

<a class="stdelete" href="#" id='<?php
		echo $msg_id; ?>' title="Delete update">
<?php
		if ($uid == $USER->id) echo '<img width="14" src="images/trash.png" style="border:none;" class="shakeimage" onMouseover="init(this);rattleimage()" onMouseout="stoprattle(this);top.focus()" onClick="top.focus()">';
		else echo "";
?></a>

<?php


		$message = str_replace("kxk", "&", $message);
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
			if (strlen($message) != strlen(utf8_decode($message))) {

				// echo 'is unicode';

				$message = str_replace('&Acirc;', '&nbsp;', $message);
				$message = str_replace('@@@@', '&', $message);
				$message = tolink(htmlentities(nl2br($message)));
			}
			else {
				$message = str_replace('&Acirc;', '&nbsp;', $message);
				$message = str_replace('@@@@', '&', $message);
				$message = tolink(htmlentities(nl2br($message)));
			}
		}
		
        //echo "$Email is ".$Email;
		
		/* Send Email if &Email=1 parameter was set. */
		if ($Email) {
			$admins = get_admins();
			$isadmin = false;
			foreach($admins as $admin) {

				// Send only to the correct recipient (which is the Moodle UserID represented by $Email) as specified in the iframe code

				if ($admin->id === $Email) {

					// echo '$USER->id is '.$USER->id.' and $admin->id is '.$admin->id.' $Email is definitely '.$Email."<br />";

					$user = get_record('user', 'id', $admin->id);
					/* Don't send an Email to yourself if you are the admin! */
					if ($USER->id != $admin->id) {

						// $subject = $USER->firstname." ".$USER->lastname." (".$USER->email.") has just posted on the '".$Desc."' Wall.";

						$subject = "Wall post from " . $USER->firstname . " " . $USER->lastname . " on '" . $Desc . "' Wall.";

						// Send off the E-mail!

						$out_message = '
            <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>' . $subject . '</title>
            </head>
            <body>' . $message . '
            </body>
            </html>';

						// now for the client side:

						$out_to = $admin->email;
						$out_subject = $subject;

						// To send HTML mail, the Content-type header must be set

						$headers = 'MIME-Version: 1.0' . "\r\n";
						$headers.= 'Content-type: text/html; charset=UTF-8' . "\r\n";

						// Additional headers

						$headers.= 'To: ' . $admin->firstname . ' ' . $admin->lastname . ' <' . $admin->email . "> \r\n";
						$headers.= 'From: ' . $USER->firstname . ' ' . $USER->lastname . '<' . $USER->email . '>' . "\r\n";
						$success = mail($out_to, $out_subject, $out_message, $headers);
						
						if ($success == "1") {
						echo "E-mail successful sent to ".$user->firstname." ".$user->lastname." (".$user->email.") !";
						} else if ($success == "emailstop") {
						echo "E-mail of ".$user->firstname." ".$user->lastname." has been DISABLED!"
						.'E-mail NOT sent';
						} else if (!$success){
						echo "Error: E-mail unsuccessful.";
						}

						
					} //if
				} //if
			} //for
		}

		echo '<b><a target="_blank" href="../user/view.php?id=' . $uid . '&course=' . $wcid . '">' . $username . '</a></b>' . ' ' . $message;

		// Ratings

		$content = 'content';
		mysql_query("set character_set_results='utf8'");
		$q = mysql_query("SELECT * FROM $content WHERE id='$msg_id'"); //GETS THE CONTENT ID
		$r = mysql_fetch_assoc($q);
?>

	
<script>
function rate(rating){ //'rating' VARIABLE FROM THE FORM in view.php
var data = 'rating='+rating+'&id=<?php
		echo $msg_id; ?>&user=<?php
		echo $uid; ?>&Likes=<?php
		echo $Likes; ?>';
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
<!-- GIVES THE CANCEL BUTTON AN UNDERLINE ON MOUSEOVER*/-->
#cancel:hover {
text-decoration: underline;
}
</style>
<?php

		// echo '$Likes AGAIN is '.$Likes.'\n';

		if ($Likes) {

			// IF $msg_id EXISTS, THEN COUNT LIKES & DISLIKES

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

				$m = '<div><img class="shakeimage" onMouseover="init(this);rattleimage()" ' . 'onMouseout="stoprattle(this);top.focus()" style="width:16; vertical-align: middle;" id="like ' . $msg_id . '" ' . 'onClick="rate($(this).attr(\'id\'))" width="16" src="' . $l . '">' . '<span><font="Tahoma"><b>&nbsp;</b>' . (($likes > 1) ? $likes . " likes" : (($likes == 1) ? $likes . " like" : "")) . '</font></span></div>';

				// EVERYTHING HERE DISPLAYED IN HTML

				echo '<br /><div id="ratings' . $msg_id . '">' . $m . '</div>';
			}
			else {
				mysql_query("set character_set_results='utf8'");
				mysql_query("INSERT INTO ratings VALUES('like','$msg_id','$ip','$uid')") or die(mysql_error()); //INSERTS INITIAL RATING

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

				$m = '<div><img class="shakeimage" onMouseover="init(this);rattleimage()" ' . 'onMouseout="stoprattle(this);top.focus()" style="width:16; ' . 'height:11;vertical-align: middle;" id="like ' . $msg_id . '" ' . 'onClick="rate($(this).attr(\'id\'))" width="16" src="' . $l . '">' . '<span><font="Tahoma"><b>&nbsp;</b>' . (($likes > 1) ? $likes . " likes" : (($likes == 1) ? $likes . " like" : "")) . '</font></span></div>';

				// EVERYTHING HERE DISPLAYED IN HTML

				echo '<br /><div id="ratings' . $msg_id . '">' . $m . '</div>';
			}
		}

?>


<div class="sttime"><?php
		time_stamp($time); ?> | <a href='#' class='commentopen' id='<?php
		echo $msg_id; ?>' title='Comment'>Comment </a></div> 

<div class="commentcontainer" id="commentload<?php
		echo $msg_id; ?>">
<?php
		include ('load_comments.php');
 ?>
</div>
<div class="commentupdate" style='display:none' id='commentbox<?php
		echo $msg_id; ?>'>
<div class="stcommentimg">
<?php

		// $ustring = "select * from mdl_user where id = ".$uid;

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
		echo $msg_id; ?>" wrap="off" onkeydown="return catchTab(this,event)"
onmouseover="if (this.title!='Type something... ') {this.className='bright';this.title='Type something...!';}"
onfocus="this.className='bright';this.title='Type something...!!';"
onmouseout="if (this.title!='The title ') {this.className='';this.title='';}"
onblur="this.className='';this.title='';"></textarea>
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
} 

 function adv_count_words($str){
     $words = 0;
     //$str = eregi_replace(" +", " ", $str);
	 $str = str_replace(" +", " ", $str);
     $array = explode(" ", $str);
     for($i=0;$i < count($array);$i++)
 	 {
         if(preg_match("/[0-9A-Za-z¿-÷ÿ-ˆ¯-ˇ]/i", $array[$i]))
		 //if (eregi("[0-9A-Za-z¿-÷ÿ-ˆ¯-ˇ]", $array[$i])) 
             $words++;			 
     }
     return $words;
 }
 
 function Flesch_Desc($n)
 {
   /* http://www.mobilefish.com/services/readability_tester/readability_tester.php
   90-100 : Very Easy 
   80-89 : Easy 
   70-79 : Fairly Easy 
   60-69 : Standard 
   50-59 : Fairly Difficult 
   30-49 : Difficult 
   0-29 : Very Difficult 
   
   Dumbed down levels:
   80-100 : Very Easy 
   70-89 : Easy  
   50-69 : Standard 
   20-49 : Difficult 
   0-19  : Very Difficult 
   */
   if($n >= 90) return "Very easy";
   else if($n >= 80) return "Easy";
   else if($n >= 70) return "Fairly easy";
   else if($n >= 60) return "Standard";
   else if($n >= 50) return "Fairly difficult";
   else if($n >= 30) return "Difficult";
   else return "Very confusing";
 }
?>

<!--
<input type="submit"   value="Comment"  id="< ?php echo $msg_id;? >" class="comment_button" />
-->
<?php

    /*

        TextStatistics Class (PHP4)
        http://code.google.com/p/php-text-statistics/

        Released under New BSD license
        http://www.opensource.org/licenses/bsd-license.php

        Calculates following readability scores (formulae can be found in wiki):
          * Flesch Kincaid Reading Ease
          * Flesch Kincaid Grade Level
          * Gunning Fog Score
          * Coleman Liau Index
          * SMOG Index
          * Automated Reability Index

        Will also give:
          * String length
          * Letter count
          * Syllable count
          * Sentence count
          * Average words per sentence
          * Average syllables per word
        
        Sample Code
        ----------------
        $statistics = new TextStatistics;
        $text = 'The quick brown fox jumped over the lazy dog.';
        echo 'Flesch-Kincaid Reading Ease: ' . $statistics->flesch_kincaid_reading_ease($text);

    */

    class TextStatistics {

        var $strEncoding = ''; // Used to hold character encoding to be used by object, if set

        /**
         * Constructor.
         *
         * @param string  $strEncoding    Optional character encoding.
         * @return void
         */
        function __construct($strEncoding = '') {
            if ($strEncoding <> '') {
                // Encoding is given. Use it!
                $this->strEncoding = $strEncoding;
            }
        }

        function TextStatistics() {
            $this->__construct();
        }

        /**
         * Gives the Flesch-Kincaid Reading Ease of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        function flesch_kincaid_reading_ease($strText) {
            $strText = $this->clean_text($strText);
            return round((206.835 - (1.015 * $this->average_words_per_sentence($strText)) - (84.6 * $this->average_syllables_per_word($strText))), 1);
        }

        /**
         * Gives the Flesch-Kincaid Grade level of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        function flesch_kincaid_grade_level($strText) {
            $strText = $this->clean_text($strText);
            return round(((0.39 * $this->average_words_per_sentence($strText)) + (11.8 * $this->average_syllables_per_word($strText)) - 15.59), 1);
        }

        /**
         * Gives the Gunning-Fog score of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        function gunning_fog_score($strText) {
            $strText = $this->clean_text($strText);
            return round((($this->average_words_per_sentence($strText) + $this->percentage_words_with_three_syllables($strText, false)) * 0.4), 1);
        }

        /**
         * Gives the Coleman-Liau Index of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        function coleman_liau_index($strText) {
            $strText = $this->clean_text($strText);
            return round( ( (5.89 * ($this->letter_count($strText) / $this->word_count($strText))) - (0.3 * ($this->sentence_count($strText) / $this->word_count($strText))) - 15.8 ), 1);
        }

        /**
         * Gives the SMOG Index of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        function smog_index($strText) {
            $strText = $this->clean_text($strText);
            return round(1.043 * sqrt(($this->words_with_three_syllables($strText) * (30 / $this->sentence_count($strText))) + 3.1291), 1);
        }

        /**
         * Gives the Automated Readability Index of text entered rounded to one digit
         * @param   strText         Text to be checked
         */
        function automated_readability_index($strText) {
            $strText = $this->clean_text($strText);
            return round(((4.71 * ($this->letter_count($strText) / $this->word_count($strText))) + (0.5 * ($this->word_count($strText) / $this->sentence_count($strText))) - 21.43), 1);
        }

        /**
         * Gives letter count (ignores all non-letters).
         * @param   strText      Text to be measured
         */
        function letter_count($strText) {
            $strText = $this->clean_text($strText); // To clear out newlines etc
            $intTextLength = 0;
            $strText = preg_replace('/[^A-Za-z]+/', '', $strText);
            $intTextLength = strlen($strText);
            return $intTextLength;
        }

        /**
         * Trims, removes line breaks, multiple spaces and generally cleans text before processing.
         * @param   strText      Text to be transformed
         */
        function clean_text($strText) {
            $strText = preg_replace('/[,:;()-]/', ' ', $strText); // Replace commans, hyphens etc (count them as spaces)
            $strText = preg_replace('/[\.!?]/', '.', $strText); // Unify terminators
            $strText = trim($strText) . '.'; // Add final terminator, just in case it's missing.
            $strText = preg_replace('/[ ]*(\n|\r\n|\r)[ ]*/', ' ', $strText); // Replace new lines with spaces
            $strText = preg_replace('/([\.])[\. ]+/', '$1', $strText); // Check for duplicated terminators
            $strText = trim(preg_replace('/[ ]*([\.])/', '$1 ', $strText)); // Pad sentence terminators
            $strText = preg_replace('/[ ]+/', ' ', $strText); // Remove multiple spaces
            $strText = preg_replace_callback('/\. [^ ]+/', create_function('$matches', 'return strtolower($matches[0]);'), $strText); // Lower case all words following terminators (for gunning fog score)
            return $strText;
        }

        /**
         * Returns sentence count for text.
         * @param   strText      Text to be measured
         */
        function sentence_count($strText) {
            $strText = $this->clean_text($strText);
            // Will be tripped up by "Mr." or "U.K.". Not a major concern at this point.
            $intSentences = max(1, strlen(preg_replace('/[^\.!?]/', '', $strText)));
            return $intSentences;
        }

        /**
         * Returns word count for text.
         * @param   strText      Text to be measured
         */
        function word_count($strText) {
            $strText = $this->clean_text($strText);
            // Will be tripped by by em dashes with spaces either side, among other similar characters
            $intWords = 1 + strlen(preg_replace('/[^ ]/', '', $strText)); // Space count + 1 is word count
            return $intWords;
        }

        /**
         * Returns average words per sentence for text.
         * @param   strText      Text to be measured
         */
        function average_words_per_sentence($strText) {
            $strText = $this->clean_text($strText);
            $intSentenceCount = $this->sentence_count($strText);
            $intWordCount = $this->word_count($strText);
            return ($intWordCount / $intSentenceCount);
        }

        /**
         * Returns average syllables per word for text.
         * @param   strText      Text to be measured
         */
        function average_syllables_per_word($strText) {
            $strText = $this->clean_text($strText);
            $intSyllableCount = 0;
            $intWordCount = $this->word_count($strText);
            $arrWords = explode(' ', $strText);
            for ($i = 0; $i < $intWordCount; $i++) {
                $intSyllableCount += $this->syllable_count($arrWords[$i]);
            }
            return ($intSyllableCount / $intWordCount);
        }

        /**
         * Returns the number of words with more than three syllables
         * @param   strText                  Text to be measured
         * @param   blnCountProperNouns      Boolean - should proper nouns be included in words count
         */
        function words_with_three_syllables($strText, $blnCountProperNouns = true) {
            $strText = $this->clean_text($strText);
            $intLongWordCount = 0;
            $intWordCount = $this->word_count($strText);
            $arrWords = explode(' ', $strText);
            for ($i = 0; $i < $intWordCount; $i++) {
                if ($this->syllable_count($arrWords[$i]) > 2) {
                    if ($blnCountProperNouns) { 
                        $intLongWordCount++;
                    } else {
                        $strFirstLetter = substr($arrWords[$i], 0, 1);
                        if ($strFirstLetter !== strtoupper($strFirstLetter)) {
                            // First letter is lower case. Count it.
                            $intLongWordCount++;
                        }
                    }
                }
            }
            return ($intLongWordCount);
        }

        /**
         * Returns the percentage of words with more than three syllables
         * @param   strText      Text to be measured
         * @param   blnCountProperNouns      Boolean - should proper nouns be included in words count
         */
        function percentage_words_with_three_syllables($strText, $blnCountProperNouns = true) {
            $strText = $this->clean_text($strText);
            $intWordCount = $this->word_count($strText);
            $intLongWordCount = $this->words_with_three_syllables($strText, $blnCountProperNouns);
            $intPercentage = (($intLongWordCount / $intWordCount) * 100);
            return ($intPercentage);
        }

        /**
         * Returns the number of syllables in the word.
         * Based in part on Greg Fast's Perl module Lingua::EN::Syllables
         * @param   strWord      Word to be measured
         */
        function syllable_count($strWord) {

            $intSyllableCount = 0;
            $strWord = strtolower($strWord);

            // Specific common exceptions that don't follow the rule set below are handled individually
            // Array of problem words (with word as key, syllable count as value)
            $arrProblemWords = Array(
                 'simile' => 3
                ,'forever' => 3
                ,'shoreline' => 2
            );
            if(!empty($arrProblemWords[$strWord]))
			   $intSyllableCount = $arrProblemWords[$strWord];
			else   
			   $intSyllableCount = 0;
			   
            if ($intSyllableCount > 0) { 
                return $intSyllableCount;
            }

            // These syllables would be counted as two but should be one
            $arrSubSyllables = Array(
                 'cial'
                ,'tia'
                ,'cius'
                ,'cious'
                ,'giu'
                ,'ion'
                ,'iou'
                ,'sia$'
                ,'[^aeiuoyt]{2,}ed$'
                ,'.ely$'
                ,'[cg]h?e[rsd]?$'
                ,'rved?$'
                ,'[aeiouy][dt]es?$'
                ,'[aeiouy][^aeiouydt]e[rsd]?$'
                ,'^[dr]e[aeiou][^aeiou]+$' // Sorts out deal, deign etc
                ,'[aeiouy]rse$' // Purse, hearse
            );

            // These syllables would be counted as one but should be two
            $arrAddSyllables = Array(
                 'ia'
                ,'riet'
                ,'dien'
                ,'iu'
                ,'io'
                ,'ii'
                ,'[aeiouym]bl$'
                ,'[aeiou]{3}'
                ,'^mc'
                ,'ism$'
                ,'([^aeiouy])\1l$'
                ,'[^l]lien'
                ,'^coa[dglx].'
                ,'[^gq]ua[^auieo]'
                ,'dnt$'
                ,'uity$'
                ,'ie(r|st)$'
            );

            // Single syllable prefixes and suffixes
            $arrPrefixSuffix = Array(
                 '/^un/'
                ,'/^fore/'
                ,'/ly$/'
                ,'/less$/'
                ,'/ful$/'
                ,'/ers?$/'
                ,'/ings?$/'
            );

            // Remove prefixes and suffixes and count how many were taken
            $intPrefixSuffixCount = 0;
            foreach ($arrPrefixSuffix as $strPrefixSuffix) {
                $intPrefixSuffixCount += preg_match_all($strPrefixSuffix, $strWord, $matches);
            }
            $strWord = preg_replace($arrPrefixSuffix, '', $strWord);

            // Removed non-word characters from word
            $strWord = preg_replace('/[^a-z]/is', '', $strWord);
            $arrWordParts = preg_split('/[^aeiouy]+/', $strWord);
            $intWordPartCount = 0;
            foreach ($arrWordParts as $strWordPart) {
                if ($strWordPart <> '') {
                    $intWordPartCount++;
                }
            }

            // Some syllables do not follow normal rules - check for them
            // Thanks to Joe Kovar for correcting a bug in the following lines
            $intSyllableCount = $intWordPartCount + $intPrefixSuffixCount;
            foreach ($arrSubSyllables as $strSyllable) {
                $intSyllableCount -= preg_match('~' . $strSyllable . '~', $strWord);
            }
            foreach ($arrAddSyllables as $strSyllable) {
                $intSyllableCount += preg_match('~' . $strSyllable . '~', $strWord);
            }
            $intSyllableCount = ($intSyllableCount == 0) ? 1 : $intSyllableCount;
            return $intSyllableCount;
        }

    }

?>
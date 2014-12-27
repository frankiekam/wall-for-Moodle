<?php

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//

function tolink($text)
{
	$text = html_entity_decode($text);
	$text = " " . $text;
	$text = preg_replace('/(((f|ht){1}tp:\/\/)[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i', '<a target="_blank" href="\\1">\\1</a>', $text);
	$text = preg_replace('/(((f|ht){1}tps:\/\/)[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i', '<a target="_blank" href="\\1">\\1</a>', $text);
	$text = preg_replace('/([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i', '\\1<a target="_blank" href="http://\\2">\\2</a>', $text);
	$text = preg_replace('/([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})/i', '<a href="mailto:\\1">\\1</a>', $text);
	return $text;
}

?>
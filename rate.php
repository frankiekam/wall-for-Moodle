<!--
Code by William Thomas, https://plus.google.com/102986274115894650336?rel=author
Source: http://wcetdesigns.com/tutorials/demos/like-dislike-button.html
Modified for Moodle by Frankie Kam

-->
<?php
$rating = $_POST["rating"];
$part = explode(" ", $rating);
$rid = $part[1];
$rating = $part[0];
$uid = (int)$_POST['user'];

// Dummy stub

$rating_type = array(
	"like",
	"dislike"
);

if (in_array($rating, $rating_type)) {
	include ("includes/db.php");

 // INCLUDES THE IMPORTANT SETTINGS
	// COUNTS LIKES & DISLIKES IF $rid EXISTS

	if ($rid) {

		// CHECKS IF USER HAS ALREADY RATED CONTENT

		$q = mysql_query("SELECT * FROM $ratings WHERE id='$rid' AND ip='$ip'");
		$r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM

		// IF USER HAS ALREADY RATED

		if ($r["rating"]) {
			if ($r["rating"] == $rating) {

				// DELETES RATING, but only if the User matches

				/*if($r["user"]==$uid)*/
				mysql_query("DELETE FROM ratings WHERE id='$rid' AND ip='$ip'");

				// else mysql_query("INSERT INTO ratings VALUES('$rating','$rid','$ip','$uid')"); //INSERTS INITIAL RATING

			}
			else {
				mysql_query("UPDATE ratings SET rating='$rating' WHERE id='$rid' AND ip='$ip'"); //CHANGES RATING
			}
		}
		else {
			mysql_query("INSERT INTO ratings VALUES('$rating','$rid','$ip','$uid')"); //INSERTS INITIAL RATING
		}

		// COUNT LIKES & DISLIKES

		$q = mysql_query("SELECT * FROM $ratings WHERE id='$rid' AND rating='like'");
		$likes = mysql_num_rows($q);
		$q = mysql_query("SELECT * FROM $ratings WHERE id='$rid' AND rating='dislike'");
		$dislikes = mysql_num_rows($q);

		// LIKE & DISLIKE IMAGES

		$l = 'images/like.png';
		$d = 'images/dislike.jpg';

		// CHECKS IF USER HAS ALREADY RATED CONTENT

		$q = mysql_query("SELECT * FROM $ratings WHERE id='$rid' AND ip='$ip'");
		$r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM

		// IF SO, THE RATING WILL HAVE A SHADOW

		if ($r["rating"] == "like") {
			$l = 'images/like_shadow.png';
		}

		if ($r["rating"] == "dislike") {
			$d = 'images/dislike_shadow.jpg';
		}

		$m = '<div><img class="shakeimage" onMouseover="init(this);rattleimage()" ' . 'onMouseout="stoprattle(this);top.focus()" style="width:16; height:16;vertical-align: middle;" ' . 'id="like ' . $rid . '" onClick="rate($(this).attr(\'id\'))" height="24" src="' . $l . '"><span>' . '<font="Tahoma"><b>&nbsp;' . showLikes($likes);
		if (showLikes($likes) == 0) $m.= '</b></font></span></div>';
		else
		if (showLikes($likes) == 1) $m.= '</b> like</font></span></div>';
		else
		if (showLikes($likes) > 1) $m.= '</b> likes</font></span></div>';

		// EVERYTHING HERE DISPLAYED IN HTML AND THE "ratings" ELEMENT FOR AJAX

		echo '<div id="ratings' . $rid . '">' . $m . '</div>';
	}
	else {
		echo "Invalid ID!!";
	}
}

function showLikes($n)
{
	if ($n > 0) return $n;
	else return " ";
}

?>
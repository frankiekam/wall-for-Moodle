<!--
/* 

// WonderWall Version 2.3
// Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info 
// Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
// http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
// You may customise the code for your Moodle site, but please maintain the above credits.
//

*/
-->
<?php
$wcid = (int)$_GET['CourseId'];
$id = (int)$_GET['Id'];
$Email = !isset($_GET["Email"]) ? 0 : (int)$_GET["Email"];
$Likes = !isset($_GET["Likes"]) ? 0 : (int)$_GET["Likes"];
$AdminOnly = !isset($_GET['AdminOnly']) ? 0 : (int)$_GET['AdminOnly'];
$WordLimit = !isset($_GET['WordLimit']) ? 0 : (int)$_GET['WordLimit'];
$Journal = !isset($_GET['Journal']) ? 0 : (int)$_GET['Journal'];
$Order = !isset($_GET["Order"]) ? 0 : (int)$_GET["Order"];
$WordStats = !isset($_GET["WordStats"]) ? 0 : (int) $_GET["WordStats"];
$MinSentences = !isset($_GET["MinSentences"]) ? 0 : (int) $_GET["MinSentences"];
$MinWords = !isset($_GET["MinWords"]) ? 0 : (int) $_GET["MinWords"];
$Desc = !isset($_GET["Desc"]) ? "The Wall" : $_GET["Desc"];

//echo '$WordStats = '.$WordStats;

error_reporting(0);
include_once 'includes/db.php';

include_once 'includes/functions.php';

include_once 'includes/tolink.php';

include_once 'includes/time_stamp.php';

include_once 'session.php';

$Wall = new Wall_Updates($wcid);
$updatesarray = $Wall->Updates($uid, $Order);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<!--<html xmlns="http://www.w3.org/1999/xhtml">-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script> 

<script type="text/javascript" src="js/scrolltopcontrol.js">
/***********************************************
* Scroll To Top Control script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Project Page at http://www.dynamicdrive.com for full source code
***********************************************/
</script>

<script type="text/javascript" language="javascript">
         $(function () {
             $('#scrlBotm').click(function () {
                 $('html, body').animate({
                     scrollTop: $(document).height()
                 },
                 800);
                 return false;
             });

             $('#scrlTop').click(function () {
                 $('html, body').animate({
                     scrollTop: '0px'
                 },
                 800);
                 return false;
             });
         });
</script>
	
<head>

<?php
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
?>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="css/nicedit.css" type="text/css" rel="stylesheet" />	
<link href="elastic/screen.css" type="text/css" rel="stylesheet" />	
<link href="css/wall.css" rel="stylesheet" type="text/css">


<script type="text/javascript" src="js/jquery.oembed.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jQuery.textareaCounter.js"></script>

<script src="js/wall.js" type="text/javascript" charset="UTF-8"></script>
<script src="js/nicEdit-Wall.js" type="text/javascript"></script>

<script type="text/javascript">
var CID 		= "<?php echo $_GET['CourseId'] ?>";
var Id 			= "<?php echo $_GET['Id'] ?>";
var Email 		= "<?php echo (int)$_GET['Email'] ?>";
var Likes 		= "<?php echo (int)$_GET['Likes'] ?>";
var AdminOnly 	= "<?php echo (int)$_GET['AdminOnly'] ?>";
var AdminOnly 	= "<?php echo (int)$_GET['AdminOnly'] ?>";
var WordLimit = "<?php echo $_GET['WordLimit'] ?>";
var Journal = "<?php echo $_GET['Journal'] ?>";
var Order = "<?php echo $_GET['Order'] ?>";
var WordStats = "<?php echo (int) $_GET['WordStats']?>";
var MinSentences = "<?php echo (int) $_GET['MinSentences']?>";
var MinWords = "<?php echo (int) $_GET['MinWords']?>";
var Desc = "<?php echo (int) $_GET['Desc']?>";


function setbg(color)
{
   document.getElementById("styled").style.background=color
}
</script>

<title>Wonderwall</title>
        <!-- http://www.jacklmoore.com/autosize -->
		<style>
			#peterText {
			   transition: all 0.25s ease-in-out;
			   font-size:14px;
			    height : 50px; 
				resize: none;
				/* background-color: #BEF9FF; */
				vertical-align: top; 
				transition: height 0.2s;
				-webkit-transition: height 0.2s; 
				-moz-transition: height 0.2s; 
				border-color: gray; 
                outline: none !important;

	            padding: 3px;
			}

            #peterText:focus { background-color: #D9F4C3; color : black;
			  border: solid 1px gray;
			  padding: 7px;
			  transition: all 0.25s ease-in-out;
  -webkit-box-shadow: inset 2px 2px 2px 0px #dddddd;
  -moz-box-shadow: inset 2px 2px 2px 0px #dddddd;
  box-shadow: inset 2px 2px 2px 0px #dddddd; 
  font-size:16px;
			
			
			}
			#peterText:blur { background-color: white; color : black;
			  padding: 7px;
  -webkit-box-shadow: inset 4px 4px 4px 0px #dddddd;
  -moz-box-shadow: inset 4px 4px 4px 0px #dddddd;
  box-shadow: inset 4px 4px 4px 0px #dddddd; 
			}

		</style>
		<script src='js/jquery.autosize.js'></script>
		<script>
			$(function(){		

				// $('#peterText').autosize({append: "\n"});'

				$('textarea').autosize({append: "\n"});
			});
		</script>
		
<title>Wonderwall</title>
</head>
<!-- #toggleButton {margin-bottom: -1.88em; margin-left: 44.4em; 
     #button {margin-top: -0.75em; margin-bottom: 1em;}
-->
        <style>
        #toggleButton {background-color: white; margin-bottom: -2.00em; margin-left: 43.6em;} 
		#button {margin-top: -0.75em; margin-bottom: 1em;}
		#button:hover {margin-top: -0.75em; margin-bottom: 1em;}
		#ui-button-text {outline: none;}
        </style>

<body onLoad="document.getElementById('toggleButton').click();">





<script type="text/javascript">
  function transcribe(words) {
    document.getElementById("peterText").value = words;
    document.getElementById("button").value = "";
    document.getElementById("peterText").focus();
  }
</script>

<a class="alignright" title="Scroll down" id="scrlBotm" href="#"><img src="images/down.png"">&nbsp;</a>
<div id="wall_container">
<div id="updateboxarea">
<h4><?php
echo stripslashes($Desc); ?></h4>
<?php

// Did the user specify that only Admins can post messages?

$AdminOnly = !isset($_GET['AdminOnly']) ? 0 : (int)$_GET['AdminOnly'];
$WordLimit = !isset($_GET['WordLimit']) ? 0 : (int)$_GET['WordLimit'];

if ($AdminOnly === 1) {
	$admins = get_admins();
	$isadmin = false;
	foreach($admins as $admin) {
		if ($USER->id == $admin->id) {
			$isadmin = true;
			break;
		}
	}
	
	// Only admins and teachers can submit postings onto the wall

	if ($isadmin == true) { 
		//echo '<button style="border-width: 0px;" name=\'toggleButton\' id=\'toggleButton\' onClick="toggleMode()"><span class="ui-button-text">Toggle<img border="0" width="8" src="images/gear.png" class="shakeimage" onMouseover="init(this);rattleimage()" onMouseout="stoprattle(this);top.focus()"/></span></button>';
		echo '<button style="border-width: 0px;" name=\'toggleButton\' id=\'toggleButton\' onClick="toggleMode()"><span class="ui-button-text">&nbsp<img border="0" width="8" src="images/gear.png" alt="Toggle edit mode" title="Toggle edit mode" class="shakeimage" onMouseover="init(this);rattleimage()" onMouseout="stoprattle(this);top.focus()"/></span></button>';
		echo '<form id="peter" accept-charset="utf-8"><div><textarea height="50" cols="120" name="peterText" id="peterText" placeholder="Say something..." onkeydown="return catchTab(this,event)"
		onmouseover="if (this.title!=\'Type something... \') {this.className=\'bright\';this.title=\'Type something...\';}"
        onfocus="this.className=\'bright\';this.title=\'Type something... \';"
        onmouseout="if (this.title!=\'The title \') {this.className=\'\';this.title=\'\';}"
        onblur="this.className=\'\';this.title=\'\';"></textarea></div></form><br>';
		echo '<input type="submit"  alt="Click to submit input" title="Click to submit input" value=" Share "  id="button"  class="button" onwebkitspeechchange="transcribe(this.value)" x-webkit-speech/>';
		if ($WordLimit) {
			echo '<div id="wordcount">';
			echo '<script type="text/javascript">';
			echo '$("textarea").textareaCounter({ limit: ' . $WordLimit . ' });';
			echo '</script>';
			echo '</div>';
		}
	}
}
else {

	// echo "Is not an admin!";

	//echo '<button style="border-width: 0px;" name=\'toggleButton\' id=\'toggleButton\' onClick="toggleMode()"><span class="ui-button-text">Toggle<img border="0" with="8" src="images/gear.png" class="shakeimage" onMouseover="init(this);rattleimage()" onMouseout="stoprattle(this);top.focus()"/></span></button>';
	echo '<button style="border-width: 0px;" name=\'toggleButton\' id=\'toggleButton\' onClick="toggleMode()"><span class="ui-button-text">&nbsp<img border="0" with="8" src="images/gear.png" alt="Toggle edit mode" title="Toggle edit mode" class="shakeimage" onMouseover="init(this);rattleimage()" onMouseout="stoprattle(this);top.focus()"/></span></button>';
	echo '<form id="peter" accept-charset="utf-8"><div><textarea height="50" cols="120" name="peterText" id="peterText" placeholder="Say something..." onkeydown="return catchTab(this,event)"
		onmouseover="if (this.title!=\'Type something... \') {this.className=\'bright\';this.title=\'Type something...\';}"
        onfocus="this.className=\'bright\';this.title=\'Type something... \';"
        onmouseout="if (this.title!=\'The title \') {this.className=\'\';this.title=\'\';}"
        onblur="this.className=\'\';this.title=\'\';"></textarea></div></form>';
	echo '<input type="submit"  value=" Share "  alt="Click to submit input" title="Click to submit input"  id="button"  class="button" onwebkitspeechchange="transcribe(this.value)" x-webkit-speech/>';
	if ($WordLimit) {
		echo '<div id="wordcount">';
		echo '<script type="text/javascript">';
		echo '$("textarea").textareaCounter({ limit: ' . $WordLimit . ' });';
		echo '</script>';
		echo '</div>';
	}
}

?>
</div>

<div style="clear: both;">
</div>
<script>

// var btn;

var area1;
function toggleMode() {

 // alert("area1 is "+area1);

 
 /* There are 3 cases: 
 area1 is undefined;
 area1 is [object Object]
 area1 is null
 We want to start out in the simple text input mode. Then if the user wants a rich text mode, he or she will then click on the 
 'Media/Text' toggle button.
 */
 
 if(typeof area1 === 'undefined')
 {
  area1 = new nicEditor({externalCSS : 'nicEditorExternal.css',/*fullPanel : true*/buttonList : ['center','forecolor','bgcolor','image','fontSize','fontFamily','fontFormat','subscript','superscript','ol','ul','indent','link','unlink','xhtml'],
  uploadURI : 'nicUpload.php'
  }).panelInstance('peterText',{hasPanel : true});
  nicEditors.findEditor('peterText').setContent($("#peterText").val());
  //$("#toggleButton span").text("Text"); 
   
   /* $('peterText').height(500); */
   nicEditors.findEditor('peterText').saveContent();
   area1.removeInstance('peterText');
   area1 = null;
   //$("#toggleButton span").text("Media");

 }
 else 
 if(!area1 || (typeof area1 === 'undefined')) {

  // area1 = new nicEditor({fullPanel : true}).panelInstance('peterText',{hasPanel : true});

  area1 = new nicEditor({externalCSS : 'nicEditorExternal.css',/*fullPanel : true*/buttonList : ['center','forecolor','bgcolor','image','fontSize','fontFamily','fontFormat','subscript','superscript','ol','ul','indent','link','unlink','xhtml'],
  placeholder:'Say something...',
  uploadURI : 'nicUpload.php'
  }).panelInstance('peterText',{hasPanel : true});

  // area1.corner();

  nicEditors.findEditor('peterText').setContent($("#peterText").val());
  //$("#toggleButton span").text("Text");
  /* $('peterText').height(100); */
 } else {
  /* $('peterText').height(500); */
  nicEditors.findEditor('peterText').saveContent();
  area1.removeInstance('peterText');
  area1 = null;
  //$("#toggleButton span").text("Media");
  }
  toggle_visibility("wordcount");
}

function toggle_visibility(id) {
       
       var e = document.getElementById(id);
       if(e.style.display == 'block') {
		  e.style.display = 'none';
	   }
       else {
		  e.style.display = 'block';
		   $("#counter-text").html("");
		  }
}

bkLib.onDomLoaded(function() { 
 toggleMode();    //This line causes the the Nicedit Save icon to disappear! 

 // btn = document.getElementById('toggleButton').click();

});
</script>


<div id='flashmessage'>
   <div id="flash" align="left"  ></div>
   <div id="content">
      
  
	  <?php
include ('load_messages.php');
 ?>
  
	  <a class="alignleft" href="#top"><!--Back to Top--></a>

</div>
</div>




</div>

</body>
</html>

<script type="text/javascript" charset="UTF-8">

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

 <style>
.shakeimage{
position:relative
}

.shakeimage2{
width:50px;height:50px;
position:relative
}


</style>
<script language="JavaScript1.2">

/*
Shake image script (onMouseover)- 
© Dynamic Drive (www.dynamicdrive.com)
For full source code, usage terms, and 100's more DHTML scripts, visit http://dynamicdrive.com
*/


// configure shake degree (where larger # equals greater shake)

var rector=1


// /////DONE EDITTING///////////

var stopit=0 
var a=1

function init(which){
stopit=0
shake=which
shake.style.left=0
shake.style.top=0
}

function rattleimage(){
if ((!document.all&&!document.getElementById)||stopit==1)
return
if (a==1){
shake.style.top=parseInt(shake.style.top)+rector+"px"
}
else if (a==2){
shake.style.left=parseInt(shake.style.left)+rector+"px"
}
else if (a==3){
shake.style.top=parseInt(shake.style.top)-rector+"px"
}
else{
shake.style.left=parseInt(shake.style.left)-rector+"px"
}
if (a<4)
a++
else
a=1
setTimeout("rattleimage()",50)
}

function stoprattle(which){
stopit=1
which.style.left=0
which.style.top=0
}

</script>
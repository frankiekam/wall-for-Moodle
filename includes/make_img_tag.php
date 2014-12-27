<?php
//WonderWall Version 2.3
//Facebook Wall Script 3.0 free version script written by Srinivas Tamada, http://www.9lessons.info 
//Major adaptation, revision and rewritten for MOODLE1.9/2.x by Frankie Kam (12/12/2012)
//http://moodurian.blogspot.com, Email: boonsengkam@gmail.com
//You may customise the code for your Moodle site, but please maintain the above credits.
//
function make_img_tag($ret)
{ 
  // look for a jpg, gif, or png reference
  // if not found, carry on as normal
  $pos = stripos($ret,".jpg") || $pos = stripos($ret,".png") || $pos = stripos($ret,".gif");
  if($pos === false) {
  ;
  } 
else 
{
  $beg=stripos($ret,"http://"); 
  if($beg===false) $beg=stripos($ret,"www.");
  if($beg===false) $beg=stripos($ret,"ftp.");
  if($beg !== false)
  {
     $imgsrc='$beg='.$beg;
     $end = stripos($ret,".jpg");
     if($end ===false) $end = stripos($ret,".gif");
     if($end ===false) $end=stripos($ret,".png");
     if($end !== false)
     {       
       $imgsrc=substr($ret,$beg,$end-$beg+4);
     }
  }
  $width=300;
  $target = " onclick=\"window.open(this.href,\\'_blank\\');return false;\"";
  $ret = preg_replace("#(^|[\n \]])([\w]+?://[\w\#$%&~/.\-;:=,?@+]*)#ise", "'\\1<insertimage><center><a href=\'".$imgsrc."\'".$target."><img src=\'\\2\' width=\'".$width."\'></img></a></center></insertimage>'", $ret);
}
return $ret;
}
?>

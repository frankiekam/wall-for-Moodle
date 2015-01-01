// Srinivas Tamada http://9lessons.info
// wall.js
function encode_utf8( s )
{
  return unescape( encodeURIComponent( s ) );
}

function decode_utf8( s )
{
  return decodeURIComponent( escape( s ) );
}

$(document).ready(function() 
{  

    bkLib.onDomLoaded(function(){
     new nicEditor({fullPanel : true, 
	    //buttonList : ['save','forecolor','bgcolor','image','upload','undo','redo','fontSize','fontFamily','fontFormat','subscript','superscript','ol','ul','indent','outdent','link','unlink','xhtml'],
		onSave : function(content, id, instance) {
	    //alert('save button clicked for element '+id+' = '+content);
		alert(content);
		//content = content.replace(/[&]plus[;]/gi,"+"); 
		content = content.replace(/[&]nbsp[;]/gi," "); 
		content = content.replace(/[&]nbsp[;]/gi," "); 
		content = content.replace(/[&]Acirc[;]/gi," "); 
		content = content.replace(/[&]lt[;]/gi,"<"); 
		content = content.replace(/[&]gt[;]/gi,">"); 
		content = content.replace(/[&]quot[;]/gi,"\""); 
		content = content.replace(/[&]amp[;]/gi,"xzx"); 
        content = content.replace("&embed","@@@@embed");
		content = content.replace("&autoplay","@@@@autoplay");
		
		var data = content;
	    
		
		data = encode_utf8(data);
		
		var dataString = 'update='+ content;
        if ( /WordLimit=1/.test(location.href) ) {
			wordNumCount = data.match(/\S+/g).length;
		   dataString = dataString + "<BR>"+" (" + wordNumCount +" words)";
		 }
		   
        //if(data=='<br>')
		if(wordNumCount == 0)
        {
          alert("Please enter some text!");
        } 
        else
          {
			 $("#flash").show();
             $("#flash").fadeIn(400).html('Loading Update...');
			 alert("Hmmm..."+Email);
			 $.ajax({type: "POST",url: "message_ajax.php?CID="+CID+"&Id="+Id+"&Email="+Email+"&Desc="+Desc+"&Likes="+Likes+"&WordStats="+WordStats+"&MinSentences="+MinSentences+"&MinWords="+MinWords, data: dataString+":mode:"+area1,cache: false,success: function(html)
             {
               $("#flash").fadeOut('fast');
               if ( /Order=1/.test(location.href) )
                   $("#content").append(html);
               else
                   $("#content").prepend(html);
               $("#update").val('');	
               $("#update").focus();  	
               $("#stexpand").oembed(updateval);
             }
             });
          }
      //Reset the TextArea to blank!
	  nicEditors.findEditor('peterText').setContent('');
	  return false;		

      } }).panelInstance('peterText');
	  
    });
	
	//This catches an image object that has "like" in its id
	/*
	$('img[id*="like"]').click(function(event) {
        // this.append wouldn't work
        //$(this).write("Click!"); 
		var id = event.target.id;
		alert("Target ID is " + event.target.id);
    });
	*/
	
    $('.button').click(function() 
	{		
	    //alert("Clicked");
	    var modeValue;
		if(area1 === null)
		   modeValue = 1;  //Pure Text Mode
		else
		   modeValue = 0;  //Media (Rich Text) Mode
		   
	    //alert("modeValue is "+modeValue);
		//area1 is equal to null means that we are in pure text mode.
        if(area1 === null)
        {
           var data = $("#peterText").val();
		   
		   
		   //data = encode_utf8(data); 
		   
		   //alert("Data is "+data);
		   //If not for the code below, the '+' symbol would have disappeard.
		   
			data = data.replace(/[&]nbsp[;]/gi," "); 
			data = data.replace(/\+/g, "qxq");
			data = data.replace(/\&/g, "kxk");
			data = data.replace(/[&]Acirc[;]/gi," "); 
		    data = data.replace(/[&]lt[;]/gi,"<"); 
		    data = data.replace(/[&]gt[;]/gi,">"); 
		    data = data.replace(/[&]quot[;]/gi,"\""); 
		    data = data.replace(/[&]amp[;]/gi,"xzx"); 
            data = data.replace("&embed","@@@@embed");
		    data = data.replace("&autoplay","@@@@autoplay");
		
	       //Strip out the HTML tags!
		   //data = data.replace(/(<([^>]+)>)/ig,"");
		   
		   $('#peterText').val(null)
		   $('#peterText').height(50);
		   //alert("!area1");
		   //alert("Data is "+data);
        } else {
		         //alert("area1");
		         nicEditors.findEditor('peterText').saveContent();
				 var data = $("#peterText").val();
				 //alert("data is "+data);
				 $('#peterText').val(null)
				 $('#peterText').height(50);
				 
				 //var data = nicEditors.findEditor('peterText').getContent();
                 /* var data = $('#peter').find('.nicEdit-main').text(); */
				 //alert("Data is "+data);			 
				 
				 
				 data = data.replace(/[&]nbsp[;]/gi," "); 
				 data = data.replace(/\+/g, "qxq");
				 data = data.replace(/[&]Acirc[;]/gi," "); 
		         data = data.replace(/[&]lt[;]/gi,"<"); 
		         data = data.replace(/[&]gt[;]/gi,">"); 
		         data = data.replace(/[&]quot[;]/gi,"\""); 
		         data = data.replace(/[&]amp[;]/gi,"xzx"); 
                 data = data.replace("&embed","@@@@embed");
		         data = data.replace("&autoplay","@@@@autoplay");
				 
		         //data = data.replace(/[&]Acirc[;]/gi," "); 
		}
		
		/* Ori
		var updateval = $("#update").val();
        var dataString = 'update='+ updateval;
        */
        
		cleanText = data.replace(/<\/?[^>]+(>|$)/g, "");
		flag = cleanText.replace(/\s/g, '');
        
		if((data===null) || (flag == ''))
        {
          alert("Please enter some text.");
        } 
        else
          {
		  
             var dataString = 'update='+ data;

			 if (/WordStats=1/.test(location.href) ) 
			    WordStats = 1;
		     else
			    WordStats = 0;
			 
             if ( /WordLimit=1/.test(location.href) ) {
		        wordNumCount = data.match(/\S+/g).length;
		        if(wordNumCount > 1)
		           dataString = dataString + "<BR>"+" (" + wordNumCount +" words)";	    
			    else
			      dataString = dataString + "<BR>"+" (" + wordNumCount +" word)";	    
            }
		
             //alert(data);
			 $("#flash").show();
             $("#flash").fadeIn(400).html('Loading Update...');
			 			 
             $.ajax({
			 type: "POST",url: "message_ajax.php?CID="+CID+"&Id="+Id+"&Email="+Email+"&Desc="+Desc+"&Likes="+Likes+"&WordStats="+WordStats+"&MinSentences="+MinSentences+"&MinWords="+MinWords, 
			 
			 data: dataString+":mode:"+modeValue,cache: false,success: function(html)
             {
               $("#flash").fadeOut('fast');
               if ( /Order=1/.test(location.href) )
                   $("#content").append(html);
               else
                   $("#content").prepend(html);
               $("#update").val('');	
               $("#update").focus();  	
               $("#stexpand").oembed(updateval);
             }
             });
          }
		//Reset the TextArea to blank!
	    nicEditors.findEditor('peterText').setContent('');
        return false;		
    });
	
    // Update Status 
    $(".update_button").click(function() 
    {
	   nicEditors.findEditor('update').saveContent();
       var updateval = $("#update").val();
       var dataString = 'update='+ updateval;
       if(updateval=='')
       {
          alert("Please Enter Some Text");
       }
       else
          {
             $("#flash").show();
             $("#flash").fadeIn(400).html('Loading Update...');
             $.ajax({	 
			 type: "POST",
			 url: "message_ajax.php?CID="+CID+"&Id="+Id+"&Email="+Email+"&Desc="+Desc+"&Likes="+Likes+"&WordStats="+WordStats+"&MinSentences="+MinSentences+"&MinWords="+MinWords, 
			 
			 data: dataString,
			 cache: false,
			 success: function(html)
             {
               $("#flash").fadeOut('fast');
               if ( /Order=1/.test(location.href) )
                   $("#content").append(html);
               else
                   $("#content").prepend(html);
			   $("#update").val('');	
               $("#update").focus();  	
               $("#stexpand").oembed(updateval);
			   }
             });
          }
       return false; 
    });
	
//commment Submit
$('.comment_button').live("click",function() 
{
   var ID = $(this).attr("id");
   var comment= $("#ctextarea"+ID).val();
   comment = comment.replace(/\+/g, "qxq");
   
   cleanText = comment.replace(/<\/?[^>]+(>|$)/g, "");
   flag = cleanText.replace(/\s/g, '');
   if((comment=='') || (flag == ''))
   {
      alert("Please enter a comment.");
   }
   else
   {  
	  comment = comment.replace(/\+/g, "qxq");
	  comment = comment.replace(/\&/g, "kxk");
      comment = comment.replace(/[&]nbsp[;]/gi," "); 
      comment = comment.replace(/[&]Acirc[;]/gi," "); 
      comment = comment.replace(/[&]lt[;]/gi,"<"); 
      comment = comment.replace(/[&]gt[;]/gi,">"); 
      comment = comment.replace(/[&]quot[;]/gi,"\""); 
      comment = comment.replace(/[&]amp[;]/gi,"xzx"); 
      comment = comment.replace("&embed","@@@@embed");
      comment = comment.replace("&autoplay","@@@@autoplay");  
	  if (/WordStats=1/.test(location.href) ) 
	     WordStats = 1;
	  else
	     WordStats = 0;	  
	  
      if ( /WordLimit=1/.test(location.href) ) {
		   wordNumCount = comment.match(/\S+/g).length;
		   if(wordNumCount > 1)
		      comment = comment + '\n' + " (" + wordNumCount +" words)";	    
			else
			  comment = comment + '\n' + " (" + wordNumCount +" word)";	    
      }
      var dataString = 'comment='+ comment + '&msg_id=' + ID;   
   
      $.ajax({
	  type: "POST",
	  url: "comment_ajax.php?CID=" + CID + "&Id="+Id+ "&WordStats=" + WordStats,
	  data: dataString,
	  cache: false,
	  success: function(html){
            $("#commentload"+ID).append(html);
            $("#ctextarea"+ID).val('');
            $("#ctextarea"+ID).focus();
         }
      });
    }
return false;
});

// commentopen 
$('.commentopen').live("click",function() 
{
   var ID = $(this).attr("id");
   $("#commentbox"+ID).slideToggle('fast');
   return false;
});	

// delete comment
$('.stcommentdelete').live("click",function() 
{
   var ID = $(this).attr("id");
   var dataString = 'com_id='+ ID;

   if(confirm("Delete comment?"))
   {
      $.ajax({type: "POST",url: "delete_comment_ajax.php?CID=" + CID + "&Id="+Id,data: dataString,cache: false,success: function(html)
	     {
            $("#stcommentbody"+ID).slideUp('fast');
         }
      });
   }
   return false;
});

// delete update
$('.stdelete').live("click",function() 
{
var ID = $(this).attr("id");
var dataString = 'msg_id='+ ID;

if(confirm("Sure you want to delete this update? There is NO undo!"))
{

$.ajax({
type: "POST",
url: "delete_message_ajax.php",
data: dataString,
cache: false,
//beforeSend: function(){ $("#stbody"+ID).animate({'backgroundColor':'#fb6c6c'},300);},
success: function(html){
 $("#stbody"+ID).slideUp();
 //$("#stbody"+ID).fadeOut(300,function(){$("#stbody"+ID).remove();});
 }
 });
}
return false;
});
});

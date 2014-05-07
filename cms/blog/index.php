<?php /*blog handling form - modal at the moment, can be altered for page content if needed (strip http headers if include)
wysihtml5 plug used here - loads at appropriate points re wysihtml5 documentation. wrap attr call after load necessary for wrapping
jQuery posts have to move to .done() .always() .fail() rather than current syntax
*/

$thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");
header("Content-Type: text/html; charset=utf-8");
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
// Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
// HTTP/1.1
header("Pragma: no-cache");
// NO CACHE
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
?><div id="modalcontent" class="w600"><?php
if($_SESSION['user']){
?><div id="blogform" class="block clear-block  h550">
<?php switch($_GET['j']){
	case "a":
$thisblogdatetime=explode(" ",date('Y-m-d H:i:s'));?>
<h3>Add blog</h3>
<form id="addblog">
<div class="clear-block  block">
<table>
<tr><td class="w100">Title:</td><td colspan="3"><input type="text" name="blogtitle" class="text w500"/></td></tr>
<tr><td>Category:</td><td><select name="category" class="text w200">
<?php $grabcategories=mysql_query("select * from cms_blogcategories order by blogcategory asc");
if(mysql_num_rows($grabcategories)){
while($categories=mysql_fetch_assoc($grabcategories)){?>
<option value="<?php print $categories['blogcategoryid'];?>"><?php print $categories['blogcategory'];?></option>
<?php }
}?></select></td><td>Access:</td><td><select name="access" class="text w200">
<option value="0">Public</option>
<option value="1">All Users</option>
<option value="2">Research Users</option>
<option value="3">Project Users</option>
</select></td></tr>
<tr><td>Date:</td><td><input type="text" name="blogdate" value="<?php print $thisblogdatetime[0];?>" class="text w100 datepicker"/></td><td>Time:</td><td><input type="text" name="blogtime" value="<?php print substr($thisblogdatetime[1],0,5);?>" class="text w100 timepicker"/></td></tr>
<tr><td>Entry:</td><td>(please use valid HTML)</td></tr>
<tr><td>Published:</td><td><select name="blogreleased" class="text" id="published">
<option value="1">Yes</option>
<option selected="selected" value="0">No</option>
</select></td><td colspan="2"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton right"><input type="button" onclick="addblog();" value="Save" class="right makebutton">
</td></tr><tr><td colspan="4"><p id="publishedstatus"></p></td></tr>
</table>
</div>
<span id="wysihtml5">
<?php include("/Users/virtualtextileproject/Sites/frame/js/wysihtml5/toolbar.inc");?>
<section>
				<textarea name="blog" id="wysihtml5-editor" class="h250" spellcheck="false" wrap="off" autofocus placeholder="Enter something ..."></textarea>
				</section>
</span>
</form>
<script>
//create WYSIHTML5
var editor = new wysihtml5.Editor("wysihtml5-editor", {
  toolbar:     "wysihtml5-editor-toolbar",
  stylesheets: ["http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css", "/frame/js/wysihtml5/css/editor.css"],
  parserRules: wysihtml5ParserRules,
  useLineBreaks:        false
});

editor.on("load", function() {
  var composer = editor.composer;
  composer.selection.selectNode(editor.composer.element.querySelector("h1"));
});
//Hide text colours and speech
$('[data-wysihtml5-command-group="foreColor"]').hide();
$('[data-wysihtml5-command="insertSpeech"]').hide();
$('#wysihtml5-editor').attr('wrap','on');
function addblog(){
	$.post('/cms/blog/blog.php?j=a', $('#addblog').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Blog Added!');
			//prepend new item to list on main page
			$('#bloglist').prepend(
					$('<tr id="'+data['id']+'"><td><a href="/blog/?id='+data['id']+'" id="blogtitlelink'+data['id']+'">'+data['name']+'</a></td><td><a class="popmodal" href="/cms/blog/index.php?j=e&id='+data['id']+'"><img src="/frame/images/icons/search.png" class="icon"></a></td><td><a class="popmodal" href="/cms/blog/index.php?j=d&id='+data['id']+'"><img src="/frame/images/icons/delete.png" class="icon"></a></td></tr>')
					);
			//popmodal new links after new elements
			$('.popmodal').magnificPopup({
				  type: 'ajax'
			});
		}
	});
	$.magnificPopup.close();
}
makedatepick();
maketimepick();
</script>
<?php break;
case "e":
$thisblog=grabinfo('cms_blog','blogid',$_GET['id'],'1');
if($thisblog['blogmadeon']){
$thisblogdatetime=explode(" ",$thisblog['blogmadeon']);
}?>
<h3>Edit blog</h3>
<form id="editblog">
<div class="clear-block  block">
<table>
<input type="hidden" name="blogid" value="<?php print $_GET['id'];?>"/>
<tr><td class="w100">Title:</td><td colspan="3"><input type="text" name="blogtitle" class="text w500" value="<?php print stripslashes($thisblog['blogtitle']);?>"/></td></tr>
<tr><td>Category:</td><td><select name="category" class="text w200">
<?php $grabcategories=mysql_query("select * from cms_blogcategories order by blogcategory asc");
if(mysql_num_rows($grabcategories)){
while($categories=mysql_fetch_assoc($grabcategories)){?>
<option <?php if($categories['blogcategoryid']==$thisblog['category']){?>
selected="selected"
<?php }?> value="<?php print $categories['blogcategoryid'];?>"><?php print $categories['blogcategory'];?></option>
<?php }
}?></select></td><td>Access:</td><td><select name="access" class="text w200">
<option value="0" <?php if($thisblog['access']==0){?>
selected="selected"
<?php }?>>Public</option>
<option value="1"<?php if($thisblog['access']==1){?>
selected="selected"
<?php }?>>All Users</option>
<option value="2"<?php if($thisblog['access']==2){?>
selected="selected"
<?php }?>>Research Users</option>
<option value="3"<?php if($thisblog['access']==3){?>
selected="selected"
<?php }?>>Project Users</option>
</select></td></tr>
<tr><td>Date:</td><td><input type="text" name="blogdate" value="<?php print $thisblogdatetime[0];?>" class="text w100 datepicker"/></td><td>Time:</td><td><input type="text" name="blogtime" value="<?php print substr($thisblogdatetime[1],0,5);?>" class="text w100 timepicker"/></td></tr>
<tr><td>Entry:</td><td colspan="3">(please use valid HTML)</td></tr>
<tr><td>Published:</td><td><select name="blogreleased" class="text" id="published">
<option <?php if($thisblog['blogreleased']=='1'){?>
selected="selected"
<?php }?> value="1">Yes</option>
<option <?php if($thisblog['blogreleased']=='0'){?>
selected="selected"
<?php }?> value="0">No</option>
</select></td><td colspan="2"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton right"><input type="button" onclick="editblog();" value="Save" class="right makebutton">
</td></tr><tr><td colspan="4"><p id="publishedstatus"></p></td></tr>
</table>
</div>
<span id="wysihtml5">
<?php include("/Users/virtualtextileproject/Sites/frame/js/wysihtml5/toolbar.inc");?>
<section>
				<textarea name="blog" id="wysihtml5-editor" class="h250" spellcheck="false" wrap="off" autofocus placeholder="Enter something ..."><?php print stripslashes($thisblog['blog']);?></textarea>
				</section>
</span>
</form>
<script>
//create WYSIHTML5
var editor = new wysihtml5.Editor("wysihtml5-editor", {
  toolbar:     "wysihtml5-editor-toolbar",
  stylesheets: ["http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css", "/frame/js/wysihtml5/css/editor.css"],
  parserRules: wysihtml5ParserRules,
  useLineBreaks:        false
});

editor.on("load", function() {
  var composer = editor.composer;
  composer.selection.selectNode(editor.composer.element.querySelector("h1"));
});
//Hide text colours and speech
$('[data-wysihtml5-command-group="foreColor"]').hide();
$('[data-wysihtml5-command="insertSpeech"]').hide();
$('#wysihtml5-editor').attr('wrap','on');
function editblog(){
	$.post('/cms/blog/blog.php?j=e', $('#editblog').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Blog Edited!');
			$('#blogtitlelink'+data['id']).text(data['name']);
		}
	});
	$.magnificPopup.close();
}
makedatepick();
maketimepick();
</script>
<?php break;
case "d": ?>
<h3>Delete blog</h3>
This will remove this blog. There is no undo.
<form id="deleteblog">
<input type="hidden" name="blogid" value="<?php print $_GET['id'];?>"><br>
<input type="button" onclick="deleteblog();" value="Delete" class="makebutton"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton">
<p id="publishedstatus"></p>
</form>
<script>
function deleteblog(){
	$.post('/cms/blog/blog.php?j=d', $('#deleteblog').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Blog Deleted!');
		}
		//need call to remove item from list on main page.
	});
	$.magnificPopup.close();
}
</script>
<?php break;
}?></div>
<?php } ?>
<script>
		$('.popmodal').magnificPopup({
			  type: 'ajax'
		});
		//publication status notification in modal
		notepubstatus();
		function notepubstatus(){
		var pubstatus=$('#published').val();
		if(pubstatus=='0'){
			$('#publishedstatus').text('This is a draft. In order to be viewed, you need to publish this information.');
			$('#publishedstatus').removeClass('good');
			$('#publishedstatus').addClass('warn');
		}else{
			$('#publishedstatus').text('This is currently published. You may hide the post, unpublishing it by returning it to draft status.');
			$('#publishedstatus').removeClass('warn');
			$('#publishedstatus').addClass('good');
		}
		}
		$('#published').change(function(){
			notepubstatus();
		});
</script>
</div>
<?php
define("ABSPATH", dirname(__FILE__));
include(ABSPATH."/../../includes/phpheader.php");
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT"); // Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // NO CACHE
?><div id="modalcontent"  class="w800"><?php if($_SESSION['user']){
$_SESSION['modalboxhistory']="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'];?>
<div id="edittopinfo" style="width:100%;" class="clear-block">
<p class="right">Not sure what you want to do? <a href='$cms_url/cms/help.php?help=editingpages' class="popmodal">Get more info</a></p>
</div>
<div id="editversions" class="left" style="width:45%">
<h2>Add a New Version</h2>
<ul class="list">

<li> <a href='<?php echo $cms_url; ?>/cms/page/addedit.php?j=a&id=<?php print $_GET['id']; ?>' class="popmodal makebutton">Add a new version of this page <?php print $_GET['id']; ?></a></li>


</ul>
<h2>Current Versions</h2>
<ul class="list"><?php

function editpagebutton($id, $unique, $access) {
	global $cms_url;
	echo "<a href=\"".$cms_url."/cms/page/addedit.php?j=e&id=".$id."&p=".$unique."\" class=\"popmodal makebutton\"><b>".
		access_to_string($access)."</b>(Unique: ".$unique.")</a>";
}
$id=$_GET['id'];
$thisrev=chkpage($id);
$thisaccess=$_SESSION['profile'];
$findcurrentpagedetails=mysql_query("select cms_contentsinfo.content_title,cms_contents.content_unique,cms_contents.content_for from cms_contents inner join cms_contentsinfo on cms_contents.content_id=cms_contentsinfo.content_id where cms_contents.content_id=".$id." and (cms_contents.content_for<=".$thisaccess." or cms_contents.content_for is NULL) order by cms_contents.content_for desc");
if (empty($findcurrentpagedetails)){
	$thispageinfo['content']= '?>Either you do not have access rights to this page, or there is no page with this id.<?php ';
}
else{
	while($thispagerow = mysql_fetch_assoc($findcurrentpagedetails)) {
		echo "<li>";
		editpagebutton($_GET['id'], $thispagerow['content_unique'], ($thispagerow['content_for']==NULL ? "" : $thispagerow['content_for']));
		echo "<a href=\"".$cms_url."/page.php?page=".$_GET['id']."&content_for=".(empty($thispagerow['content_for']) ? "NULL" : $thispagerow['content_for'])."\" target=\"_blank\" class=\"makebutton\">View</a>";
		echo "</li>";
}
}
?>
</ul>
</div>
<div id="editrevisions" class="right" style="width:45%;">
<h2>Last 10 Versions</h2>
<?php $findpastpagedetails=mysql_query("select * from cms_contents_history where cms_contents_history.content_id=".$id." order by cms_contents_history.content_timestamp desc limit 0,10");
if (empty($findpastpagedetails)){
	$thispageinfo['content']= '?>Either you do not have access rights to this page, or there is no page with this id.<?php ';
}else{?><table class="admintable"><?php
	while($findpastpagerow = mysql_fetch_assoc($findpastpagedetails)){
$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';?>
<tr class="<?php print $even_odd;?>"><td><a href="/cms/page/addedit.php?j=e&id=<?php print $_GET['id'];?>&p=<?php print $findpastpagerow['content_unique'];?>" class="popmodal"><?php print access_to_string($findpastpagerow['content_for']);?> (Unique <?php print $findpastpagerow['content_unique']." @ ".$findpastpagerow['content_timestamp'];?>)</a></td></tr><?php
	}?></table><?php
}
?>
</div>
<?php }else{ ?>
Looks like your session has logged out for inactivity! Please login again to make edits to this page!
<?php } ?>
<script>
		$('.popmodal').magnificPopup({
			  type: 'ajax'
		});
</script>
</div>
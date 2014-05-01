<?php $thisabspath = "/Users/virtualtextileproject/Sites/workingcopy";
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
?><div id="modalcontent" class="w450"><?php
if($_SESSION['user']){
?><div id="collectionform" class="clearfix">
<?php switch($_GET['j']){
	case "a": ?>
	<h3>Add Collection</h3>
	<form id="addcollection">
	<table>
<tr><td class="w100">Name:</td><td><input type="text" name="collectionname" class="text w350"/></td></tr>
<tr><td class="w100">Abbreviation:</td><td><input type="text" name="collectionabbreviation" class="text w70" value=""/></td></tr>
<tr><td colspan="2"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton right"><input type="button" onclick="addcollection();" value="Save" class="makebutton right">
</td></tr>
</table>
</form>
<script>
function addcollection(){
	$.post('/cms/collection/collection.php?j=a', $('#addcollection').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Collection Added!');
			$('#collectionlist').prepend(
					$('<tr id="collection'+data['id']+'"><td class="w200" id="collectiondate'+data['id']+'">'+data['date']+'</td><td><a href="/collection/?id='+data['id']+'" id="collectionnamelink'+data['id']+'">'+data['name']+'</a></td><td><a class="popmodal" href="/cms/collection/index.php?j=e&id='+data['id']+'"><img src="/frame/images/icons/search.png" class="icon"></a></td><td><a class="popmodal" href="/cms/collection/index.php?j=d&id='+data['id']+'"><img src="/frame/images/icons/delete.png" class="icon"></a></td></tr>')
					);
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
$thiscollection=grabinfo('cms_collections','collectionid',$_GET['id'],'1');
?>
	<h3>Edit Collection</h3>
	<form id="editcollection">
	<table>
<tr><td class="w100">Name:</td><td><input type="text" name="collectionname" class="text w350" value="<?php print stripslashes($thiscollection['collectionname']);?>"/></td></tr>
<tr><td class="w100">Abbreviation:</td><td><input type="text" name="collectionabbreviation" class="text w70" value="<?php print stripslashes($thiscollection['collectionabbreviation']);?>"/></td></tr>
<tr><td colspan="2"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton right"><input type="button" onclick="editcollection();" value="Save" class="makebutton right">
</td></tr>
</table>
<input type="hidden" name="collectionid" value="<?php print $_GET['id'];?>">
</form>
<script>
function editcollection(){
	$.post('/cms/collection/collection.php?j=e', $('#editcollection').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Collection Edited!');
			$('#collectionnamelink'+data['id']).text(data['name']);
			$('#collectiondate'+data['id']).text(data['date']);
		}
	});
	$.magnificPopup.close();
}
</script>
<?php break;
case "d": ?>
<h3>Delete collection</h3>
This will remove this collection and all associated tasks, milestones, events, and financials. There is no undo.
<form id="deletecollection">
<table>
<tr><td><input type="hidden" name="collectionid" value="<?php print $_GET['id'];?>">
<input type="button" onclick="deletecollection();" value="Delete" class="makebutton"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton">
</td></tr></table>
</form>
<script>
function deletecollection(){
	$.post('/cms/collection/collection.php?j=d', $('#deletecollection').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Collection Deleted!');
		}
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
</script>
</div>
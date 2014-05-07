<?php /*calendar handling form - modal at the moment, can be altered for page content if needed (strip http headers if include)
jQuery posts have to move to .done() .always() .fail() rather than current syntax
*/

$thisabspath = "/Users/virtualtextileproject/Sites/workingcopy";
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
?><div id="modalcontent" class="w550"><?php
if($_SESSION['user']){
?><div id="calendarform" class="clearfix">
<?php switch($_GET['j']){
	case "a": ?>
	<h3>Add To Calendar</h3>
	<form id="addcalendar">
	<table>
<tr><td class="w100">Title:</td><td colspan="3"><input type="text" name="calendartitle" class="text w450"/></td></tr>
<tr><td>Type:</td><td colspan="3"><select name="calendartype" class="text">
<option selected>select a type</option>
<?php $getcalendartypes=mysql_query("Select * from cms_calendartypes order by calendartype asc");
if(mysql_num_fields($getcalendartypes)){
while($getcalendartyperows=mysql_fetch_assoc($getcalendartypes)){?>
<option value="<?php print $getcalendartyperows['calendartypeid'];?>"><?php print $getcalendartyperows['calendartype'];?></option>
<?php }
}?>
</select></td></tr>
<tr><td colspan="4">Info:</td></tr>
<tr><td colspan="4"><textarea name="calendarinfo" class="text p100 h200"></textarea></td></tr>
<tr><td>Start Date:</td><td><input type="text" name="calendarstartdate" class="text w100 datepicker"/></td><td>Time:</td><td><input type="text" name="calendarstarttime" class="text w100 timepicker"/></td></tr>
<tr><td>End Date:</td><td><input type="text" name="calendarenddate" class="text w100 datepicker"/></td><td>Time:</td><td><input type="text" name="calendarendtime" class="text w100 timepicker"/></td></tr>
<tr><td>Timezone:</td><td colspan="3"><select name="timezone" id="timezone" class="text">
<?php //cull necessary timezones from the following, and map them to north america, europe, asia?
static $regions = array(
    'Africa' => DateTimeZone::AFRICA,
    'America' => DateTimeZone::AMERICA,
    'Asia' => DateTimeZone::ASIA,
    'Australia' => DateTimeZone::AUSTRALIA,
    'Europe' => DateTimeZone::EUROPE,
    'Indian' => DateTimeZone::INDIAN,
);

foreach ($regions as $name => $mask) {
    $tzlist[$name] = DateTimeZone::listIdentifiers($mask);
}
//print_r($tzlist);

foreach ($tzlist as $key => $zone){?>
<optgroup label="<?php print $key;?>"><?php
foreach($zone as $thiszone){
$cleaned=str_replace($key."/","",$thiszone);
$cleaned=str_replace("_"," ",$cleaned);
if($thiszone=='America/Montreal'){?>
<option selected="selected" value="<?php print $thiszone;?>"><?php print $cleaned;?></option>
<?php
}else{?>
<option value="<?php print $thiszone;?>"><?php print $cleaned;?></option>
<?php
}
}?></optgroup><?php
}
?>
</select></td></tr>
<tr><td>Show the time?:</td><td colspan="3"><select name="calendarshowtime" class="text">
<option value="0">No</option>
<option selected value="1">Yes</option>
</select></td></tr>
<tr><td>Location:</td><td colspan="3"><input type="text" name="calendarlocation" class="text w450"/></td></tr>
<tr><td>Access:</td><td><select name="calendaraccess" class="text">
<option value="0">Public</option>
<option selected value="1">Project</option>
<option value="2">Admin</option>
</select></td><td>Published:</td><td><select name="calendarreleased" class="text" id="published">
<option value="1">Yes</option>
<option selected="selected" value="0">No</option>
</select></td></tr>
<tr><td colspan="4"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton right"><input type="button" onclick="addcalendar();" value="Save" class="makebutton right">
</td></tr>
</table>
</form>
<script>
function addcalendar(){
	$.post('/cms/calendar/calendar.php?j=a', $('#addcalendar').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Calendar Event Added!');
			//prepend new item to list on main page
			$('#calendarlist').prepend(
					$('<tr id="calendar'+data['id']+'"><td class="w200" id="calendardate'+data['id']+'">'+data['date']+'</td><td><a href="/calendar/?id='+data['id']+'" id="calendartitlelink'+data['id']+'">'+data['name']+'</a></td><td><a class="popmodal" href="/cms/calendar/index.php?j=e&id='+data['id']+'"><img src="/frame/images/icons/search.png" class="icon"></a></td><td><a class="popmodal" href="/cms/calendar/index.php?j=d&id='+data['id']+'"><img src="/frame/images/icons/delete.png" class="icon"></a></td></tr>')
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
$thiscalendar=grabinfo('cms_calendar','calendarid',$_GET['id'],'1');
if($thiscalendar['calendarstart']){
$thiscalendarstartdatetime=explode(" ",$thiscalendar['calendarstart']);
}
if($thiscalendar['calendarend']){
$thiscalendarenddatetime=explode(" ",$thiscalendar['calendarend']);
}?>
	<h3>Edit calendar</h3>
	<form id="editcalendar">
	<table>
<tr><td class="w100">Title:</td><td colspan="3"><input type="text" name="calendartitle" class="text w450" value="<?php print stripslashes($thiscalendar['calendartitle']);?>"/></td></tr>
<tr><td>Type:</td><td colspan="3"><select name="calendartype" class="text">
<option selected>select a type</option>
<?php $getcalendartypes=mysql_query("Select * from cms_calendartypes order by calendartype asc");
if(mysql_num_fields($getcalendartypes)){
while($getcalendartyperows=mysql_fetch_assoc($getcalendartypes)){?>
<option <?php if($getcalendartyperows['calendartypeid']==$thiscalendar['calendartype']){?>
selected="selected"
<?php }?> value="<?php print $getcalendartyperows['calendartypeid'];?>"><?php print $getcalendartyperows['calendartype'];?></option>
<?php }
}?>
</select></td></tr>
<tr><td colspan="4">Info:</td></tr>
<tr><td colspan="4"><textarea name="calendarinfo" class="text p100 h200"><?php print stripslashes($thiscalendar['calendarinfo']);?></textarea></td></tr>
<tr><td>Start Date:</td><td><input type="text" name="calendarstartdate" class="text w100 datepicker" value="<?php print $thiscalendarstartdatetime[0];?>"/></td><td>Time:</td><td><input type="text" name="calendarstarttime" class="text w100 timepicker" value="<?php print substr($thiscalendarstartdatetime[1],0,5);?>"/></td></tr>
<tr><td>End Date:</td><td><input type="text" name="calendarenddate" class="text w100 datepicker" value="<?php print $thiscalendarenddatetime[0];?>"/></td><td>Time:</td><td><input type="text" name="calendarendtime" class="text w100 timepicker" value="<?php print substr($thiscalendarenddatetime[1],0,5);?>"/></td></tr>
<tr><td>Timezone:</td><td colspan="3"><select name="timezone" id="timezone" class="text">
<?php static $regions = array(
    'Africa' => DateTimeZone::AFRICA,
    'America' => DateTimeZone::AMERICA,
    'Asia' => DateTimeZone::ASIA,
    'Australia' => DateTimeZone::AUSTRALIA,
    'Europe' => DateTimeZone::EUROPE,
    'Indian' => DateTimeZone::INDIAN,
);

foreach ($regions as $name => $mask) {
    $tzlist[$name] = DateTimeZone::listIdentifiers($mask);
}
//print_r($tzlist);

foreach ($tzlist as $key => $zone){?>
<optgroup label="<?php print $key;?>"><?php
foreach($zone as $thiszone){
$cleaned=str_replace($key."/","",$thiszone);
$cleaned=str_replace("_"," ",$cleaned);
if($thiszone==$thiscalendar['calendartimezone']){?>
<option selected="selected" value="<?php print $thiszone;?>"><?php print $cleaned;?></option>
<?php
}else{?>
<option value="<?php print $thiszone;?>"><?php print $cleaned;?></option>
<?php
}
}?></optgroup><?php
}
?>
</select></td></tr>
<tr><td>Show the time?:</td><td colspan="3"><select name="calendarshowtime" class="text">
<option <?php if($thiscalendar['calendarshowtime']=='0'){?>
selected="selected"
<?php }?> value="0">No</option>
<option <?php if($thiscalendar['calendarshowtime']=='1'){?>
selected="selected"
<?php }?> value="1">Yes</option>
</select></td></tr>
<tr><td>Location:</td><td colspan="3"><input type="text" name="calendarlocation" class="text w450" value="<?php print stripslashes($thiscalendar['calendarlocation']);?>"/></td></tr>
<tr><td>Access:</td><td><select name="calendaraccess" class="text">
<option <?php if($thiscalendar['calendaraccess']=='0'){?>
selected="selected"
<?php }?> value="0">Public</option>
<option <?php if($thiscalendar['calendaraccess']=='1'){?>
selected="selected"
<?php }?> value="1">Project</option>
<option <?php if($thiscalendar['calendaraccess']=='2'){?>
selected="selected"
<?php }?> value="2">Admin</option>
</select></td><td>Published:</td><td><select name="calendarreleased" class="text" id="published">
<option <?php if($thiscalendar['calendarreleased']=='1'){?>
selected="selected"
<?php }?> value="1">Yes</option>
<option <?php if($thiscalendar['calendarreleased']=='0'){?>
selected="selected"
<?php }?> value="0">No</option>
</select></td></tr>
<tr><td colspan="4"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton right"><input type="button" onclick="editcalendar();" value="Save" class="makebutton right">
</td></tr>
</table>
<input type="hidden" name="calendarid" value="<?php print $_GET['id'];?>">
</form>
<script>
function editcalendar(){
	$.post('/cms/calendar/calendar.php?j=e', $('#editcalendar').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Calendar Event Edited!');
			$('#calendartitlelink'+data['id']).text(data['name']);
			$('#calendardate'+data['id']).text(data['date']);
		}
	});
	$.magnificPopup.close();
}
makedatepick();
maketimepick();
</script>
<?php break;
case "d": ?>
<h3>Delete calendar</h3>
This will remove this calendar and all associated tasks, milestones, events, and financials. There is no undo.
<form id="deletecalendar">
<table>
<tr><td><input type="hidden" name="calendarid" value="<?php print $_GET['id'];?>">
<input type="button" onclick="deletecalendar();" value="Delete" class="makebutton"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton">
</td></tr></table>
</form>
<script>
function deletecalendar(){
	$.post('/cms/calendar/calendar.php?j=d', $('#deletecalendar').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('Calendar Event Deleted!');
		}
		//need call to remove item from list on main page.
	});
	$.magnificPopup.close();
}
</script>
<?php break;
}?><p id="publishedstatus"></p></div>
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
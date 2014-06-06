<?php $thisabspath = "/Users/virtualtextileproject/Sites";
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
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
?><div id="modalcontent"  class="w500"><?php
if($_SESSION['user']){
?><div id="userform" class="clearfix">
<?php switch($_GET['j']){
	case "a": ?>
<h3>Add user</h3>
<form id="adduser">
<table>
<tr><td class="w100">Firstname:</td><td><input type="text" name="firstname" class="text w200"/></td><td>Address:</td></tr>
<tr><td>Middlename:</td><td><input type="text" name="middlename" class="text w200"/></td><td rowspan="6"><textarea name="address" class="text w200 h100"></textarea></td></tr>
<tr><td>Lastname:</td><td><input type="text" name="lastname" class="text w200"/></td></tr>
<tr><td>Email:</td><td><input type="text" name="email" class="text w200"/></td></tr>
<tr><td>Userid:</td><td><input type="text" name="userid" class="text w200"/></td></tr>
<tr><td>Password:</td><td><input type="text" name="password" id="userpwd" class="text w200"/></td></tr>
<tr><td>Profile:</td><td><select id="userprofile" name="profile" class="text w200">
<?php $grabprofiles=mysql_query("select * from cms_userprofiles where profileid<7");
if(mysql_num_rows($grabprofiles)){
while($profiles=mysql_fetch_assoc($grabprofiles)){?>
<option value="<?php print $profiles['profileid'];?>"><?php print $profiles['profile'];?></option>
<?php }
}?></select></td></tr>
<tr><td colspan="3">Bio:</td></tr>
<tr><td colspan="3"><textarea name="bio" id="bio" class="text p100 h200"></textarea></td></tr>
<tr><td colspan="3"><input type="button" onclick="adduser();" value="Save" class="makebutton"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton"></td></tr></table>
</form>
<script>
function adduser(){
	$.post('/cms/user/user.php?j=a', $('#adduser').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('User Added!');
		}
	});
	$.magnificPopup.close();
}
</script>
<?php break;
case "e":
$thisuser=grabinfo('cms_users','userid',$_GET['id'],'1');?>
<h3>Edit user</h3>
<form id="edituser">
<input type="hidden" name="userid" value="<?php print $_GET['id'];?>"/>
<table>
<tr><td class="w100">Firstname:</td><td><input type="text" name="firstname" class="text w200" value="<?php print stripslashes($thisuser['firstname']);?>"/></td><td>Address:</td></tr>
<tr><td>Middlename:</td><td><input type="text" name="middlename" class="text w200" value="<?php print stripslashes($thisuser['middlename']);?>"/></td><td rowspan="4"><textarea name="address" class="text h100 w200"><?php print stripslashes($thisuser['address']);?></textarea></td></tr>
<tr><td>Lastname:</td><td><input type="text" name="lastname" class="text w200" value="<?php print stripslashes($thisuser['lastname']);?>"/></td></tr>
<tr><td>Email:</td><td><input type="text" name="email" class="text w200" value="<?php print stripslashes($thisuser['email']);?>"/></td></tr>
<?php /*?>
<tr><td>Userid:</td><td><input type="text" name="userid" class="text w200" value="<?php print stripslashes($thisuser['userid']);?>"/></td></tr>
<tr><td>Password:</td><td><input type="text" name="password" id="userpwd" class="text w200" value="<?php print stripslashes($thisuser['password']);?>"/></td></tr>
<?php */?>
<tr><td>Profile:</td><td><select id="userprofile" name="profile" class="text w200">
<?php $grabprofiles=mysql_query("select * from cms_userprofiles where profileid<7");
if(mysql_num_rows($grabprofiles)){
while($profiles=mysql_fetch_assoc($grabprofiles)){?>
<option <?php if($profiles['profileid']==$thisuser['profile']){?>
selected="selected"
<?php }?> value="<?php print $profiles['profileid'];?>"><?php print $profiles['profile'];?></option>
<?php }
}?></select></td></tr>
<tr><td colspan="3">Bio:</td></tr>
<tr><td colspan="3"><textarea name="bio" id="bio" class="text p100 h200"><?php print stripslashes($thisuser['bio']);?></textarea></td></tr>
<tr><td colspan="3"><input type="button" onclick="edituser();" value="Save" class="makebutton"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton"></td></tr></table>
</form>
<script>
function edituser(){
	$.post('/cms/user/user.php?j=e', $('#edituser').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('User Edited!');
		}
	});
	$.magnificPopup.close();
}
</script>
<?php break;
case "d": ?>
<h3>Delete user</h3>
This will remove this user. There is no undo.
<form id="deleteuser">
<input type="hidden" name="userid" value="<?php print $_GET['id'];?>"><br>
<input type="button" onclick="deleteuser();" value="Delete" class="makebutton">
</form>
<script>
function deleteuser(){
	$.post('/cms/user/user.php?j=d', $('#deleteuser').serialize(),
	function(data){
		if(data['status']=='ok'){
			alert('User Deleted!');
		}
	});
	$.magnificPopup.close();
}
</script>
<?php break;
}?></div><p></p>
<?php } ?>
<script>
		$('.popmodal').magnificPopup({
			  type: 'ajax'
		});
</script>
</div>
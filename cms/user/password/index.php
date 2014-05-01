<?php $thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../../includes/phpheader.php");
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
?><div id="modalcontent" class="w300"><?php
if($_SESSION['user']){
?><div id="userform" class="clearfix">
<form id="passwordreset">
<?php if($_SESSION['user']){?>
<h3>Change Password</h3>
<table>
<tr><td class="strong">Old Password</td><td><input type="password" name="oldpwd" class="text w200"  value=""></td></tr>
<tr><td class="strong">New Password</td><td><input type="password" name="newpwd" class="text w200"  value=""></td></tr>
<tr><td class="strong">Confirm</td><td><input type="password" name="confirmed" class="text w200"  value=""></td></tr>
</table>
<?php }else{?>
<h3>Reset Password</h3>
<p>If you forgot your password, you can reset it here using your email address and userid.</p>
<table>
<tr><td class="strong">Userid</td><td><input type="text" name="userid" class="text w200"  value=""></td></tr>
<tr><td class="strong">Email</td><td><input type="text" name="email" class="text w200"  value=""></td></tr>
</table>
<?php }?>
<input type="button" class="makebutton" value="Reset Password" onclick="resetpwd();"><input type="button" onclick="$.magnificPopup.close();" value="Cancel" class="makebutton">
</form>
<script>
function resetpwd(){
	$.post('/cms/user/password/reset.php', $('#passwordreset').serialize(),
			function(data){
				if(data['status']=='ok'){
					alert('Password Reset!');
				}
			});
			$.nmTop().close();
}
</script>
<?php } ?>
<script>
		$('.popmodal').magnificPopup({
			  type: 'ajax'
		});
</script>
</div>
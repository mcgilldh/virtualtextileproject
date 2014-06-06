<?php $thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../../includes/phpheader.php");
header("Content-Type: application/json; charset=utf-8");
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
// Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
// HTTP/1.1
header("Pragma: no-cache");
header("Content-Type: application/json");
$status=array();
if($_POST['email'] && $_POST['userid']){
	$random=rand(0,999);
	$newpassword=substr(sha1(sha1($random."a43fg")),0,10);
	$sql="update cms_users set password=sha1('".$newpassword."') where userid='".mysql_real_escape_string($_POST['userid'])."' and email='".mysql_real_escape_string($_POST['email'])."'";
	$doreset=mysql_query($sql);

	if(mysql_errno()!=0){
		$status['status']="fail";
	}else{
		$to = $_POST['email'];
		$from = "web@virtualtextileproject.org";
		$headers = "From: $from";
		$subject = "Reset Password";
		$body="The Virtual Textile Project\n\nThe password for user '".mysql_real_escape_string($_POST['userid'])."' has been reset as requested. It is now \n\n".$newpassword."\n\nTo change this password after logging in, visit ".$cms_url."/account/.\n\nIf you forget your password again, it can be reset using the same method.\n\n\nIf you have questions regarding your account, please email a webmaster at web@virtualtextileproject.org.";
		$send = mail($to, $subject, $body, $headers);
		if($_SESSION['user']){
			//if for some reason a user needs to reset their password WHILE logged in...
			$status['newpwd']=$newpassword;
		}
		$status['name']=$body."..".$headers."..".$to;
		$status['status']="ok";
	}





}elseif($_SESSION['user'] && $_POST['oldpwd'] && $_POST['newpwd']){
	$pwdsql="select password from cms_users where userid='".$_SESSION['user']."'";
	$grabcurrentpwd=mysql_query($pwdsql);
	if(mysql_num_rows($grabcurrentpwd)){
		$pwd=mysql_fetch_assoc($grabcurrentpwd);

		if( ($_POST['newpwd']== $_POST['confirmed']) && (sha1($_POST['oldpwd'])==$pwd['password'])){
			$sql="update cms_users set password=sha1('".$_POST['newpwd']."') where userid='".$_SESSION['user']."'";
			$doreset=mysql_query($sql);
			if(mysql_errno()!=0){
				$status['status']="fail";
			}else{
				$status['status']="ok";
				$_SESSION['password']=sha1($_POST['newpwd']);
			}
		}else{
			$status['status']="fail";
		}
	}else{
		$status['status']="fail";
	}
}
print json_encode($status);
?>
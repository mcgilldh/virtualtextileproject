<?php include("../../../../frame/pieces/phpheader_a.php"); 
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache'); 
header("Content-Type: application/json");
if($_POST['email']){
$usersql="select userid from cms_users where email='".$_POST['email']."' limit 0,1";
$chkuser=mysql_query($usersql);
if(mysql_num_rows($chkuser)){
$resetuser=mysql_fetch_assoc($chkuser);
$thisuser=$resetuser['userid'];
$thisemail=$_POST['email'];
}
}elseif($_POST['u']){
$thisuser=$_POST['u'];
$emailsql="select email from cms_users where userid='".$_POST['u']."' limit 0,1";
$chkemail=mysql_query($emailsql);
if(mysql_num_rows($chkemail)){
$resetuser=mysql_fetch_assoc($chkemail);
$thisemail=$resetuser['email'];
}
}

if($thisuser){
$random=rand(0,999);
$newpassword=substr(sha1(sha1($random."a43fg")),0,10);
$sql="update cms_users set password=sha1('".$newpassword."') where userid='".$thisuser."'";
$doreset=mysql_query($sql);

$status=array();
if(mysql_errno()!=0){
$status['status']="fail";
}else{
if($_SESSION['user']==$thisuser){
setcookie("PHPSESSID","",time()-3600,"/","www.makingpublics.org");
setcookie("formsuser", $_SESSION['hash'], time()-3600,"/","www.makingpublics.org");
$_SESSION['password']=sha1($newpassword);
}

$to = $thisemail; 
$from = "web@makingpublics.org"; 
$headers = "From: $from"; 
$subject = "Reset Password"; 
$body="Making Publics\n\nThe password for user '".$thisuser."' has been reset as requested. It is now \n\n".$newpassword."\n\nTo change this password after logging in, visit $cms_url/myinfo/settings/password.php.\n\nIf you forget your password again, it can be reset using your email address at $cms_url/admin/forgot.php.\n\n\nPlease do not reply to this email. If you have questions regarding your account, please email a webmaster at info@makingpublics.org.";
$send = mail($to, $subject, $body, $headers); 
$status['status']="success";
$status['name']=$thisemail;
} 
}else{
$status['status']="fail";
}
print json_encode($status);
?>
<?php include("../../../../frame/pieces/phpheader_a.php"); 
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache'); 
header("Content-Type: application/json");

$newpwd=sha1($_POST['newpassword']);
$confpwd=sha1($_POST['confnewpassword']);
$oldpwd=sha1($_POST['oldpassword']);
if($newpwd=$confpwd){
if($oldpwd==$_SESSION['password']){
$sql="update cms_users set password='".$newpwd."' where userid='".$_SESSION['user']."'";
mysql_query($sql);
$status= array();
if(mysql_errno()!=0){
$status['status']="fail";
}else{
$status['status']="success";
setcookie("PHPSESSID","",time()-3600,"/","www.makingpublics.org");
setcookie("mapsuser", $_SESSION['hash'], time()-3600,"/","www.makingpublics.org");
$_SESSION['password']=$newpwd;
}
}else{
$status['status']="fail";
}
}else{
$status['status']="fail";
}

print json_encode($status);
?>
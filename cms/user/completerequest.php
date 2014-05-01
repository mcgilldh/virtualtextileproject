<?php include("../../../../frame/pieces/phpheader_a.php"); 
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache'); 
header("Content-Type: application/json");

if($_SESSION['user'] && $_SESSION['profile']>1){
$sql="update cms_getinvolved set complete=1 where requestid=".$_POST['r'];

mysql_query($sql);
if(mysql_errno()!=0){
$showuser= array();
$showuser['status']="fail";
}else{
$showuser= array();
$showuser['status']="success";
$status=TRUE;
} 
print json_encode($showuser);
}
?>
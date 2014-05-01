<?php include("../../../../frame/pieces/phpheader_a.php");
if($_SESSION['user']){
mysql_query("update cms_docs set docowner='".$_GET['o']."' where docid=".$_GET['doc']);
if(mysql_errno()!=0){
$result['status']='Failed, try again';
}else{
$result['status']='Success!';
}
header("Content-Type: application/json");
print json_encode($result);
}
?>
<?php include("../../../../frame/pieces/phpheader_a.php");
header("Content-Type: application/json");
if($_SESSION['user']){
$finddoc=mysql_query("select * from cms_docs where docid=" . $_POST['doc']);
if(mysql_num_rows($finddoc)){
while($finddocrow=mysql_fetch_assoc($finddoc)){
$docpath=$finddocrow['docpath'];
$docowner=$finddocrow['docpath'];
}
}
$docauth=FALSE;
if($_SESSION['user']==$docowner){
$docauth=TRUE;
}elseif($_SESSION['profile']>=2){
$docauth=TRUE;
}
if($docauth){
if($_POST['d']=="y"){
$sql="delete from cms_docassociations where docid=". $_POST['doc'];
mysql_query($sql);
$sql="delete from cms_docs where docid=". $_POST['doc'];
mysql_query($sql);
unlink($docpath);
}else{
$sql="delete from cms_docassociations where docid=". $_POST['doc']." and linkeditem='" . $_POST['type']."' and linkid=".$_POST['id'];
mysql_query($sql);
}
}
}
?>
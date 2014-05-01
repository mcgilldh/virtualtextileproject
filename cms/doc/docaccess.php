<?php include("../../../../frame/pieces/phpheader_a.php");
header("Content-Type: application/json");
if($_SESSION['user']){
switch(trim($_POST['value'])){
case "Private":
$access=2;
break;
case "Users":
$access=1;
break;
case "Anyone":
$access=0;
break;
}
$docid=trim($_POST['editorid'],'docaccess');
mysql_query("update cms_docs set docaccess=".$access." where docowner='".$_SESSION['user']."' and docid=".$docid);
switch($access){
case "2":
print "Private";
break;
case "1":
print "Users";
break;
case "0":
print "Anyone";
break;
}
}
?>
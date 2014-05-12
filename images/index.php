<?php //error_reporting(E_ALL);
//ini_set('display_errors', '1');

include ("/Users/virtualtextileproject/Sites/includes/phpheader.php");

header("Expires: Mon, 01 Jul 2003 00:00:00 GMT"); // Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
$docid=$_GET['id'];
if(!empty($docid)){
$getdoc=mysql_query("select * from IMG_detail where Textile_img_id=".$docid." limit 0,1",$oadbcon);
if(mysql_num_rows($getdoc)){
$getdocinfo=mysql_fetch_assoc($getdoc);


if($_GET['dis']){
	header('Content-Disposition: attachment; filename="'.$getdocinfo['VT_Tracking'].'.jpg"');
}else{
	header('Content-Disposition: inline; filename="'.$getdocinfo['VT_Tracking'].'.jpg"');
}
header("Content-type: image/jpeg");
print $getdocinfo['Textile_img'];
}else{
	header("HTTP/1.0 404 Not Found");
}
}else{
	header("HTTP/1.0 404 Not Found");
}

// ...
?>

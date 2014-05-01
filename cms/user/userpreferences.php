<?php include("../../../../frame/pieces/phpheader_a.php"); 
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache'); 
header("Content-Type: application/json");
$job=$_GET['j'];
if($_SESSION['user']){

if(empty($_POST['userid'])){
$useridformstr="NULL";
}else{
$useridformstr="'".$_POST['userid']."'";
}
if(empty($_POST['preferenceset'])){
$preferencesetformstr="'full'";
}else{
$preferencesetformstr= "'".mysql_real_escape_string(ucfirst($_POST['preferenceset']))."'";
}
if(empty($_POST['defaulthome'])){
$defaulthomeformstr="NULL";
}else{
$defaulthomeformstr= "'".mysql_real_escape_string(ucfirst($_POST['defaulthome']))."'";
}
if(empty($_POST['commentdisplay'])){
$commentdisplayformstr=10;
}else{
$commentdisplayformstr= mysql_real_escape_string(ucfirst($_POST['commentdisplay']));
}
if(empty($_POST['citationstyle'])){
$citationstyleformstr="'Chicago'";
}else{
$citationstyleformstr= "'".mysql_real_escape_string($_POST['citationstyle'])."'";
}
if(empty($_POST['styletype'])){
$styletypeformstr="'Bibliographic'";
}else{
$styletypeformstr= "'".mysql_real_escape_string($_POST['styletype'])."'";
}
if($_POST['dashboardon']==1){
$dashboardonformstr=1;
}else{
$dashboardonformstr=0;
}
if(empty($_POST['favlibrary'])){
$favlibraryformstr=0;
}else{
$favlibraryformstr=mysql_real_escape_string($_POST['favlibrary']);
}

if(empty($_POST['favcatalogue'])){
$favcatalogueformstr=0;
}else{
$favcatalogueformstr=mysql_real_escape_string($_POST['favcatalogue']);
}


if(empty($_POST['defaultbrowser'])){
$defaultbrowserformstr="NULL";
}else{
$defaultbrowserformstr="'".mysql_real_escape_string($_POST['defaultbrowser'])."'";
}

if(empty($_POST['filefolder'])) {
$filefolderformstr="NULL";
}else{
$filefolderformstr=mysql_real_escape_string($_POST['filefolder']);
}

if(empty($_POST['faceimage'])) {
$faceimageformstr="NULL";
}else{
$faceimageformstr="'".mysql_real_escape_string($_POST['faceimage'])."'";
}
if(empty($_POST['connectionproxy'])) {
$connectionproxyformstr="NULL";
}else{
$connectionproxyformstr="'".mysql_real_escape_string($_POST['connectionproxy'])."'";
}
if(empty($_POST['proxyid'])) {
$proxyidformstr="NULL";
}else{
$proxyidformstr="'".mysql_real_escape_string($_POST['proxyid'])."'";
}

if(empty($_POST['proxyport'])) {
$proxyportformstr="'8080'";
}else{
$proxyportformstr="'".mysql_real_escape_string($_POST['proxyport'])."'";
}

if(empty($_POST['proxypwd'])) {
$proxypwdformstr="NULL";
}else{
$proxypwdformstr="'".mysql_real_escape_string($_POST['proxypwd '])."'";
}

if(empty($_POST['cloud'])) {
$cloudformstr="NULL";
}else{
$cloudformstr="'".mysql_real_escape_string($_POST['cloud'])."'";
}
if(empty($_POST['emaildigest'])){
$emaildigestformstr="'week'";
}else{
$emaildigestformstr="'".$_POST['emaildigest']."'";
}
if(empty($_POST['emaildirect'])){
$emaildirectformstr=0;
}else{
$emaildirectformstr=$_POST['emaildirect'];
}
switch($job){
case "a":
$sql="INSERT INTO cms_userpreferences (userid,proxypwd,preferenceset,defaulthome,commentdisplay,citationstyle,styletype,proxyid,favlibrary,filefolder,favcatalogue,defaultbrowser,faceimage,connectionproxy,dashboardon,cloud,proxyport,emaildigest,emaildirect)";
$sql=$sql . " VALUES ";
$sql=$sql . "(" . $useridformstr . ",";
$sql=$sql . $proxypwdformstr . ",";
$sql=$sql . $preferencesetformstr . ",";
$sql=$sql . $defaulthomeformstr . ",";
$sql=$sql . $commentdisplayformstr . ",";
$sql=$sql . $citationstyleformstr . ",";
$sql=$sql . $styletypeformstr . ",";
$sql=$sql . $proxyidformstr . ",";
$sql=$sql . $favlibraryformstr . ",";
$sql=$sql . $filefolderformstr . ",";
$sql=$sql . $favcatalogueformstr . ",";
$sql=$sql . $defaultbrowserformstr . ",";
$sql=$sql . $faceimageformstr . ",";
$sql=$sql . $connectionproxyformstr . ",";
$sql=$sql . $dashboardonformstr . ",";
$sql=$sql . $cloudformstr . ",";
$sql=$sql . $proxyportformstr . ",";
$sql=$sql . $emaildigestformstr . ",";
$sql=$sql . $emaildirectformstr . ")";

break;
case "e":
$sql="update cms_userpreferences set defaulthome=". $defaulthomeformstr . ",commentdisplay=". $commentdisplayformstr . ",citationstyle=". $citationstyleformstr . ",styletype=". $styletypeformstr . ",proxyid=". $proxyidformstr . ",favlibrary=". $favlibraryformstr . ",filefolder=". $filefolderformstr . ",favcatalogue=". $favcatalogueformstr . ",defaultbrowser=". $defaultbrowserformstr . ",faceimage=". $faceimageformstr . ",connectionproxy=". $connectionproxyformstr . ",dashboardon=". $dashboardonformstr . ",cloud=". $cloudformstr . ",proxyport=". $proxyportformstr . ",proxypwd =". $proxypwdformstr . ",emaildigest =". $emaildigestformstr . ",emaildirect =". $emaildirectformstr . " where userid=". $useridformstr." and preferenceset=". $preferencesetformstr;
break;
}
mysql_query($sql);
if(mysql_errno()!=0){
$showuser= array();
$showuser['id']="fail";
}else{
if($useridformstr){
$showuser= array();
$showuser['id']=$_POST['userid'];
$showuser['prefs']=$preferencesetformstr;
$status=TRUE;
}
} 
}
print json_encode($showuser);
?>
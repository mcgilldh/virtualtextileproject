<?php session_name("mapssession");
session_start();
global $con;
$con = mysql_connect("www.makingpublics.org:3306","makingpublicsdb","h4b3rm45",true);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  mysql_select_db("makingpublics", $con);
  mysql_query('set names "utf8"');
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT"); // Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
$docid=$_GET['id'];
if(!empty($docid)){
$getdoc=mysql_query("select * from cms_docs where docid=".$docid." limit 0,1");
if(mysql_num_rows($getdoc)){
$getdocinfo=mysql_fetch_assoc($getdoc);

$opendoc=FALSE;
switch($getdocinfo['docaccess']){
case "2":
if($_SESSION['user']==$getdocinfo['docowner']){
$opendoc=TRUE;
}
break;
case "1":
if($_SESSION['user']){
$opendoc=TRUE;
}
break;
case "0":
$opendoc=TRUE;
break;
}

if($opendoc){
$thisfile=$getdocinfo['docpath'];
$filename=$getdocinfo['doctitle'];
$fixednamearr=strtr($filename,"����`()!$'?: ,&+-/���������������������������������������������������������������������","------------------SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
$fixedname = strtolower(str_replace("[^A-Za-z0-9.]", "", $fixednamearr));

/*header("Content-Length: " . $size);
// If it's a large file we don't want the script to timeout, so:
set_time_limit(600); 
header('X-Pad: avoid browser bug');
Header('Cache-Control: no-cache');
if($thisdoc['mime']=="audio/mpeg"){
header("Content-Transfer-Encoding: binary"); 
header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
header('Content-Disposition: inline; filename="'.urlencode(basename($thisfile)).'"');
}else{
header("Content-type: ".$thisdoc['mime']);
header('Content-Disposition: attachment; filename="'.urlencode(basename($thisfile)).'"');
}
ob_clean();
flush();
readfile_chunked($thisfile);
*/
send_file($thisfile,$getdocinfo['docmime']);
}else{
header("Location: $cms_url/auth.php?e=403");
}
}else{
header("Location: $cms_url/auth.php?e=404");
}
}else{
header("Location: $cms_url/auth.php?e=noid");
}

function send_file($path,$mime) {
    session_write_close();
    ob_end_clean();
    if (!is_file($path) || connection_status()!=0)
        return(FALSE);

    //to prevent long file from getting cut off from     //max_execution_time

    set_time_limit(0);

    $name=basename($path);

    //filenames in IE containing dots will screw up the
    //filename unless we add this

    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
        $name = preg_replace('/\./', '%2e', $name, substr_count($name, '.') - 1);

    //required, or it might try to send the serving     //document instead of the file

    header("Cache-Control: ");
    header("Pragma: ");
	header("Content-Transfer-Encoding: binary"); 
	switch($mime){
	case "audio/mpeg":
header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
header('Content-Disposition: inline; filename="'.urlencode($name).'"');
break;
case "image/jpeg":
case "image/gif":
case "image/png":
header('Content-Disposition: inline; filename="'.urlencode($name).'"');
header("Content-type: ".$mime);
break;
default:
if($_GET['dis']=='inline'){
header('Content-Disposition: inline; filename="'.urlencode($name).'"');
}else{
header('Content-Disposition: attachment; filename="'.urlencode($name).'"');
}
header("Content-type: ".$mime);
}
header("Content-Length: " .(string)(filesize($path)) );

    if($file = fopen($path, 'rb')){
        while( (!feof($file)) && (connection_status()==0) ){
            print(fread($file, 1024*8));
            flush();
        }
        fclose($file);
    }
    return((connection_status()==0) and !connection_aborted());
}

function readfile_chunked($filename) {
 $chunksize = 1*(1024*1024); // how many bytes per chunk
 $buffer = '';
 $handle = fopen($filename, 'rb');
 if ($handle === false) {
   return false;
 }
 while (!feof($handle)) {
   $buffer = fread($handle, $chunksize);
   print $buffer;
 }
 return fclose($handle);
}

// ...
?>

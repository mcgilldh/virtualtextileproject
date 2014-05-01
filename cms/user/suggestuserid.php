<?php include("../../../../frame/pieces/phpheader_a.php"); 
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache'); 
header("Content-Type: application/json");

if($_SESSION['user']){
if($_POST['ln'] && $_POST['fn']){
$firstname=strtr(str_replace(" ","_",$_POST['fn']),"���������������������������������������������������������������������","SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
$firstname = str_replace("[^A-Za-z0-9_]", "", $firstname);
$firstname = str_replace(".", "", $firstname);
$lastname=strtr(str_replace(" ","_",$_POST['ln']),"���������������������������������������������������������������������","SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
$lastname = str_replace("[^A-Za-z0-9_]", "", $lastname);
$lastname = str_replace(".", "", $lastname);
$middlename=strtr(str_replace(" ","_",$_POST['mn']),"���������������������������������������������������������������������","SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
$middlename = str_replace("[^A-Za-z0-9_]", "", $middlename);
$middlename = str_replace(".", "", $middlename);
$chkusers=mysql_query("select userid from cms_users where lastname='".$_POST['ln']."' and firstname='".$_POST['fn']."'");
if(mysql_num_rows($chkusers)){
$newuser['status']='exists';
$thisuser=mysql_fetch_assoc($chkusers);
$newuser['founduser']=$thisuser['userid'];
}else{
$testid=substr($firstname,0,1).$lastname;
$testid=str_replace("'","",$testid);
$testid=stripslashes($testid);
$chkusers=mysql_query("select userid from cms_users where userid='".$testid."'");
if(mysql_num_rows($chkusers)){
if(!empty($_POST['mn'])){
$newtestid=substr($firstname,0,1).substr($middlename,0,1).$lastname;
}else{
$newtestid=substr($firstname,0,1).$lastname.substr(sha1($firstname),0,1);
}
$newtestid=str_replace("'","",$newtestid);
$newtestid=stripslashes($newtestid);
$newchkusers=mysql_query("select userid from cms_users where userid='".$newtestid."'");
if(mysql_num_rows($newchkusers)){
$newuser['status']='success';
$newuser['close']='yes';
$newuser['id']=$newtestid.'2';
}else{
$newuser['status']='success';
$newuser['close']='yes';
$newuser['id']=$newtestid;
}
}else{
$newuser['status']='success';
$newuser['id']=$testid;
}
}
}else{
$newuser['status']='fail';
}
print json_encode($newuser);
}
?>
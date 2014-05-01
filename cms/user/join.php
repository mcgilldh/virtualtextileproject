<?php include("../../../../frame/pieces/phpheader_a.php"); 
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache'); 
header("Content-Type: application/json");

if($_POST['age']=='over') {
if(empty($_POST['lastname'])){
$lastnameformstr="NULL";
}else{
$lastnameformstr= "'".ucfirst($_POST['lastname'])."'";
}
if(empty($_POST['firstname'])){
$firstnameformstr="NULL";
}else{
$firstnameformstr= "'".ucfirst($_POST['firstname'])."'";
}
if(empty($_POST['middlename'])){
$middlenameformstr="NULL";
}else{
$middlenameformstr= "'".ucfirst($_POST['middlename'])."'";
}
if(empty($_POST['email'])){
$emailformstr="NULL";
}else{
$emailformstr= "'".mysql_real_escape_string($_POST['email'])."'";
}

if(empty($_POST['iam'])) {
$iamformstr="'scholar'";
}else{
$iamformstr="'".mysql_real_escape_string($_POST['iam'])."'";
}

if($_POST['verif_box']==$_SESSION['formverification']){
$sql="INSERT INTO cms_getinvolved (newlastname,newfirstname,newmiddlename,newemail,newiam)";
$sql=$sql . " VALUES ";
$sql=$sql . "(" . $lastnameformstr . ",";
$sql=$sql . $firstnameformstr . ",";
$sql=$sql . $middlenameformstr . ",";
$sql=$sql . $emailformstr . ",";
$sql=$sql . $iamformstr . ")";
}
mysql_query($sql);
if(mysql_errno()!=0){
$showuser= array();
$showuser['id']="fail";
}else{
$showuser= array();
$showuser['id']="success";
$showuser['name']=$_POST['firstname']." ".$_POST['lastname'];
$status=TRUE;
mail('web@makingpublics.org','New MaPs User Request',"There is a new MaPs User Request for\n\n".$showuser['name'].". Please visit the admin page to complete the account.");
}
}else{
$showuser['id']="fail";
}
print json_encode($showuser);
?>
<?php session_name("mapssession");
session_start();
include("../../../../frame/pieces/phpheader_a.php"); 
if(!empty($_SESSION['user'])){
$newpwd=$_POST['password'];
$userinfo=builduserinfo($_SESSION['user']);
$oldpwd=$userinfo['password'];
if($newpwd==$oldpwd){
print "TRUE";
}else{
print "FALSE";
}
}
?>
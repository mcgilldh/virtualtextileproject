<?php
//
//
// *******GLOBAL VARIABLE DEFINITIONS*********
//
//
$cms_domain="www.virtualtextileproject.org";
$cms_url="http://".$cms_domain;
/*
These are defined in cms_vre
$admin_email="webmin@virtualtextileproject.org";
$site_name="Virtual Textile Project";
*/
include("dbconfig.php");
$thisincludespath="/Users/virtualtextileproject/Sites/includes";
set_include_path(get_include_path() . PATH_SEPARATOR . $thisabspath. PATH_SEPARATOR . $thisincludespath);

//General Housekeeping
session_name("vtp");
session_start();
if(!empty($_COOKIE['vtp'])){
  session_id($_COOKIE['vtp']);
}
setcookie(session_name(), session_id(), 1, '/',".".$cms_domain);
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
setlocale(LC_ALL, 'UTF8');
include("connection.php");
include("functions.php");
if(!$_SESSION['user']){
	$_SESSION ['citationstyle'] = "Chicago";
	$_SESSION ['styletype'] = "Notation";
	$_SESSION ['country'] = "Canada";
	$_SESSION ['zippostalcode'] = "H3A2T6";
	$_SESSION ['profile'] = "0";
}

//Set up VRE
$getvreinfo = mysql_query ( "select * from cms_vre limit 0,1" );
if (mysql_num_rows ( $getvreinfo )) {
	$site_info = mysql_fetch_assoc ( $getvreinfo );
}

$site_name=$site_info['vretitle'];
$admin_email=$site_info['vreemail'];
$log=TRUE;?>

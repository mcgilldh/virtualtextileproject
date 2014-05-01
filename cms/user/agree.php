<?php $thisabspath = "/Users/virtualtextileproject/Sites/workingcopy";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");
if($_SESSION['user']){
	header("Content-Type: application/json; charset=utf-8");
	header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
	// Past date
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	// HTTP/1.1
	header("Pragma: no-cache");
	// NO CACHE
	//error_reporting(E_ALL);
	//ini_set('display_errors', TRUE);
	if($_SESSION['user']){

		if($_POST['agree']=='1') {
			$agree="1";
		}else{
			$agree="0";
		}

		$sql="update cms_users set agree=". $agree . " where userid='". $_SESSION['user']."'";
		//print $sql;
		mysql_query($sql);
		if(mysql_errno()!=0){
			$showuser= array();
			$showuser['id']=$_POST['userid'];
			$showuser['status']="fail";
		}else{
			$showuser= array();
			$showuser['status']="ok";
			$showuser['id']=$_POST['userid'];
			$showuser['name']=$_POST['firstname']." ".$_POST['lastname'];
		}
	}
	print json_encode($showuser);
}
?>
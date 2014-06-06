<?php $thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");
if($_SESSION['user']){
	header("Content-Type: text/html; charset=utf-8");
	header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
	// Past date
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	// HTTP/1.1
	header("Pragma: no-cache");
	// NO CACHE
	//error_reporting(E_ALL);
	//ini_set('display_errors', TRUE);

	$job=$_GET['j'];
	if($_SESSION['user']){

		if(empty($_POST['userid'])){
			$userid="NULL";
		}else{
			$userid="'".$_POST['userid']."'";
		}
		if(empty($_POST['lastname'])){
			$lastname="NULL";
		}else{
			$lastname= "'".ucfirst($_POST['lastname'])."'";
		}
		if(empty($_POST['firstname'])){
			$firstname="NULL";
		}else{
			$firstname= "'".ucfirst($_POST['firstname'])."'";
		}
		if(empty($_POST['middlename'])){
			$middlename="NULL";
		}else{
			$middlename= "'".ucfirst($_POST['middlename'])."'";
		}
		if(empty($_POST['email'])){
			$email="NULL";
		}else{
			$email= "'".mysql_real_escape_string($_POST['email'])."'";
		}

		if($_POST['lockuser']==1){
			$lockuser=1;
		}else{
			$lockuser=0;
		}

		if(empty($_POST['address'])) {
			$address="NULL";
		}else{
			$address="'".mysql_real_escape_string($_POST['address'])."'";
		}

		if(empty($_POST['bio'])) {
			$bio="NULL";
		}else{
			$bio="'".mysql_real_escape_string($_POST['bio'])."'";
		}

		if($_POST['agree']==1) {
			$agree="1";
		}else{
			$agree="0";
		}
		if($_POST['userinterview']==1) {
			$userinterview="1";
		}else{
			$userinterview="0";
		}

		if(empty($_POST['profile'])) {
			$profile="1";
		}else{
			$profile=mysql_real_escape_string($_POST['profile']);
		}

		$madedate=date('Y-m-d H:i:s');
		$newuserhash="'".sha1($madedate.$userid)."'";

		if(empty($_POST['pwddate'])) {
			$pwddate="'".$madedate."'";
		}else{
			$pwddate="'".mysql_real_escape_string($_POST['pwddate'])."'";
		}


		if(empty($_POST['password'])) {
			$password="'".sha1('needsapwd')."'";
		}else{
			$password="'".sha1($_POST['password'])."'";
		}

		switch($job){
			case "a":
				if($_SESSION['profile']>1){
					$sql="INSERT INTO cms_users (userid,password,pwddate,lastname,firstname,middlename,profile,email,address,lockuser,hashid,madedate,agree,userinterview,bio)";
					$sql=$sql . " VALUES ";
					$sql=$sql . "(" . $userid . ",";
					$sql=$sql . $password . ",";
					$sql=$sql . $pwddate . ",";
					$sql=$sql . $lastname . ",";
					$sql=$sql . $firstname . ",";
					$sql=$sql . $middlename . ",";
					$sql=$sql . $profile . ",";
					$sql=$sql . $email . ",";
					$sql=$sql . $address . ",";
					$sql=$sql . $lockuser . ",";
					$sql=$sql . $newuserhash . ",'";
					$sql=$sql . $madedate . "',";
					$sql=$sql . $agree . ",";
					$sql=$sql . $userinterview . ",";
					$sql=$sql . $bio . ")";
				}
				break;
			case "e":
				$sql="update cms_users set pwddate=". $pwddate . ",lastname=". $lastname . ",firstname=". $firstname . ",middlename=". $middlename . ",profile=". $profile . ",email=". $email . ",address=". $address . ",lockuser=". $lockuser . ",agree=". $agree . ",userinterview=". $userinterview . ",bio=". $bio . " where userid=". $userid;
				break;
			case "d":
				$sql="delete from cms_users where userid=". $userid;
				break;
		}
		//print $sql;
		mysql_query($sql);
		if(mysql_errno()!=0){
			$showuser= array();
			$showuser['id']=$_POST['userid'];
			$showuser['status']="fail";
		}else{
			if($userid){
				$showuser= array();
				$showuser['status']="ok";
				$showuser['id']=$_POST['userid'];
				$showuser['name']=$_POST['firstname']." ".$_POST['lastname'];
				$status=TRUE;
			}
		}
	}
	print json_encode($showuser);
}
?>
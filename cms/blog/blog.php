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
	if(empty($_POST['blogid'])){
		$blogid="NULL";
	}else{
		$blogid=$_POST['blogid'];
	}
	if(empty($_POST['blogtitle'])){
		$blogtitle="NULL";
	}else{
		$blogtitle= str_replace('"',"'",cleancurly($_POST['blogtitle']));
		$blogtitle= "'".mysql_real_escape_string($blogtitle)."'";
	}
	if(empty($_POST['category'])){
		$category="NULL";
	}else{
		$category= $_POST['category'];
	}

	if(empty($_POST['blog'])){
		$blog="NULL";
	}else{
		$blog= "'".mysql_real_escape_string(cleancurly($_POST['blog']))."'";
	}

	if(empty($_POST['blogreleased'])){
		$blogreleased=0;
	}else{
		$blogreleased=$_POST['blogreleased'];
	}

	if(empty($_POST['blogaccess'])) {
		$blogaccess="0";
	}else{
		$blogaccess=$_POST['blogaccess'];
	}

	$titlehash=preg_replace("/[^a-z0-9]/i", "-", trim($_POST['blogtitle'],"/ "));

if (empty($_POST['blogdate'])) {
        $blogmadeon = "NULL";
    } else {
        $blogmadeon = "'" . mysql_real_escape_string($_POST['blogdate']) . " ".mysql_real_escape_string($_POST['blogtime']) .":00'";
    }

	$blogmadeby="'".$_SESSION['user']."'";


	switch($job){
		case "a":
			if($_SESSION['profile']>1){
				$sql="INSERT INTO cms_blog (blogtitle,category,blog,`blogreleased`,blogmadeon,blogmadeby,blogaccess,blogtitlehash)";
				$sql=$sql . " VALUES ";
				$sql=$sql . "(" . $blogtitle . ",";
				$sql=$sql . $category . ",";
				$sql=$sql . $blog . ",";
				$sql=$sql . $blogreleased . ",";
				$sql=$sql . $blogmadeon . ",";
				$sql=$sql . $blogmadeby . ",";
				$sql=$sql . $blogaccess . ",sha1('";
				$sql=$sql . $titlehash . "'))";
			}
			break;
		case "e":
			$sql="update cms_blog set blogtitle=". $blogtitle . ",category=". $category . ",blogmadeon=". $blogmadeon . ",blog=". $blog . ",`blogreleased`=". $blogreleased . ",blogaccess=". $blogaccess . ",blogtitlehash=sha1('".$titlehash."') where blogid=". $blogid;
			break;
		case "d":
			$sql="delete from cms_blog where blogid=". $blogid;
			break;
	}
	//print $sql;
	mysql_query($sql);
	if(mysql_errno()!=0){
		$showblog= array();
		$showblog['id']=$blogid;
		$showblog['status']="fail";
	}else{
		$blogid=grabnewestid('cms_blog','blogid');
		if($blogid){
			$showblog= array();
			$showblog['status']="ok";
			$showblog['id']=$blogid;
			$showblog['name']=$_POST['blogtitle'];
			$status=TRUE;
		}
	}
	print json_encode($showblog);
}
?>
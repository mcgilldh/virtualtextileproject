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

	$job=$_GET['j'];
	if($_POST['collectionid']){
		$collectionid=$_POST['collectionid'];
	}
	if($_POST['collectionname']){
		$collectionname= str_replace('"',"'",cleancurly($_POST['collectionname']));
		$collectionname= mysql_real_escape_string($collectionname);


	$collectionurl=strtolower(preg_replace("/[^a-z0-9]/i", "-", trim($_POST['collectionname'],"/ ")));
	$collectionhash=sha1(strtolower(preg_replace("/[^a-z0-9]/i", "-", trim($_POST['collectionname'],"/ "))));
	$collectionmadeby="'".$_SESSION['user']."'";
	$collectionmadeon="'".date('Y-m-d H:i:s')."'";
	}

	if(empty($_POST['collectionabbreviation'])){
		$collectionabbreviation="NULL";
	}else{
		$collectionabbreviation= "'".mysql_real_escape_string(strtoupper(preg_replace("/[^a-z0-9]/i", "", trim($_POST['collectionabbreviation']))))."'";
	}

	switch($job){
		case "a":
				$sql="INSERT INTO cms_collections (collectionname,collectionabbreviation,collectionurl,collectionhash)";
				$sql=$sql . " VALUES ";
				$sql=$sql . "('" . $collectionname . "',";
				$sql=$sql . $collectionabbreviation. ",'";
				$sql=$sql . $collectionurl . "','";
				$sql=$sql . $collectionhash . "')";
			break;
		case "e":
			$sql="update cms_collections set collectionname='". $collectionname . "',collectionabbreviation=".$collectionabbreviation.",collectionhash='".$collectionhash."',collectionurl='".$collectionurl."' where collectionid=". $collectionid;
			break;
		case "d":
			$sql="delete from cms_collections where collectionid=". $collectionid;
			break;
	}
	//print $sql;
	mysql_query($sql);
	if(mysql_errno()!=0){
		$showcollection= array();
		$showcollection['id']=$collectionid;
		$showcollection['status']="fail";
	}else{
		if($job=='a'){
		$collectionid=grabnewestid('cms_collections','collectionid');
		}
		if($collectionid){
			$showcollection= array();
			$showcollection['status']="ok";
			$showcollection['id']=$collectionid;
			$showcollection['name']=$_POST['collectionname'];
			$status=TRUE;
		}
	}
	print json_encode($showcollection);
}
?>
<?php /*calendar entry page
if we construct a query builder, this page should be revised entirely.

checks for user, could check for profile as well, if we need it. Clear up path, using htaccess setting for includes?
Update to mysqli from mysql, procedural or PDO?

*/
$thisabspath = "/Users/virtualtextileproject/Sites/workingcopy";
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
	if(empty($_POST['calendarid'])){
		//if null, can only be add - otherwise fail
		$job='a';
		$calendarid="NULL";
	}else{
		$calendarid=$_POST['calendarid'];
	}
	if(empty($_POST['calendartitle'])){
		$calendartitle="NULL";
	}else{
		$calendartitle= str_replace('"',"'",cleancurly($_POST['calendartitle']));
		$calendartitle= "'".mysql_real_escape_string($calendartitle)."'";
	}
	if(empty($_POST['calendarlocation'])){
		$calendarlocation="NULL";
	}else{
		$calendarlocation= "'".mysql_real_escape_string(cleancurly($_POST['calendarlocation']))."'";
	}

	if(empty($_POST['calendarinfo'])){
		$calendarinfo="NULL";
	}else{
		$calendarinfo= "'".mysql_real_escape_string(cleancurly($_POST['calendarinfo']))."'";
	}

	if(empty($_POST['calendartype'])){
		$calendartype="1";
	}else{
		$calendartype=$_POST['calendartype'];
	}

	if(empty($_POST['calendarshowtime'])){
		$calendarshowtime="0";
	}else{
		$calendarshowtime="1";
	}

	if(empty($_POST['calendaraccess'])) {
		$calendaraccess="0";
	}else{
		$calendaraccess=$_POST['calendaraccess'];
	}
	if(empty($_POST['calendarreleased'])) {
		$calendarreleased="0";
	}else{
		$calendarreleased=$_POST['calendarreleased'];
	}

	if (empty($_POST['calendarstartdate'])) {
		$calendarstart = "NULL";
	} else {
		$calendarstart = "'" . mysql_real_escape_string($_POST['calendarstartdate']) . " ".mysql_real_escape_string($_POST['calendarstarttime']) .":00'";
	}
	if (empty($_POST['calendarenddate'])) {
		$calendarend = "NULL";
	} else {
		$calendarend = "'" . mysql_real_escape_string($_POST['calendarenddate']) . " ".mysql_real_escape_string($_POST['calendarendtime']) .":00'";
	}
	if (empty($_POST['timezone'])) {
		$calendartimezone = "'America/Montreal'";
	} else {
		$calendartimezone = "'" . mysql_real_escape_string($_POST['timezone']) . "'";
	}

	//create hash value of title for url matching
	$titlehash=sha1(preg_replace("/[^a-z0-9]/i", "-", trim($_POST['calendartitle'],"/ ")));
	$calendarmadeby="'".$_SESSION['user']."'";
	$calendarmadeon="'".date('Y-m-d H:i:s')."'";

	switch($job){
		case "a":
				$sql="INSERT INTO cms_calendar (calendartitle,calendarlocation,calendarinfo,calendarshowtime,calendartype,calendarstart,calendarend,calendarmadeon,calendarmadeby,calendartimezone,calendarreleased,calendaraccess,calendartitlehash)";
				$sql=$sql . " VALUES ";
				$sql=$sql . "(" . $calendartitle . ",";
				$sql=$sql . $calendarlocation . ",";
				$sql=$sql . $calendarinfo . ",";
				$sql=$sql . $calendarshowtime . ",";
				$sql=$sql . $calendartype . ",";
				$sql=$sql . $calendarstart. ",";
				$sql=$sql . $calendarend . ",";
				$sql=$sql . $calendarmadeon. ",";
				$sql=$sql . $calendarmadeby . ",";
				$sql=$sql . $calendartimezone . ",";
				$sql=$sql . $calendarreleased . ",";
				$sql=$sql . $calendaraccess . ",'";
				$sql=$sql . $titlehash . "')";
			break;
		case "e":
			$sql="update cms_calendar set calendartitle=". $calendartitle . ",calendarlocation=". $calendarlocation . ",calendarshowtime=".$calendarshowtime.",calendarinfo=". $calendarinfo . ",calendartype=". $calendartype . ",calendaraccess=". $calendaraccess . ",calendarstart=". $calendarstart . ",calendarend=". $calendarend . ",calendartimezone=".$calendartimezone.",calendarreleased=".$calendarreleased.",calendartitlehash='".$titlehash."' where calendarid=". $calendarid;
			break;
		case "d":
			$sql="delete from cms_calendar where calendarid=". $calendarid;
			break;
	}
	//print $sql;
	mysql_query($sql);
	if(mysql_errno()!=0){
		$showcalendar= array();
		$showcalendar['id']=$calendarid;
		$showcalendar['status']="fail";
	}else{
		if($job=='a'){
		$calendarid=grabnewestid('cms_calendar','calendarid');
		/*replace with mysql_last_id*/
		}
		if($calendarid){
			$showcalendar= array();
			$showcalendar['status']="ok";
			$showcalendar['id']=$calendarid;
			$showcalendar['name']=$_POST['calendartitle'];
			$showcalendar['date']=date("M d Y g:i A", strtotime($_POST['calendarstartdate']. " ".mysql_real_escape_string($_POST['calendarstarttime']) .":00"));
			$status=TRUE;
		}
	}
	/*return results as json for handling on front end*/
	print json_encode($showcalendar);
}
?>
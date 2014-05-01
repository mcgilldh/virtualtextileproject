<?php
/*********************************************************
 * The MIT License (MIT)

Copyright (c) 2013 Matthew Milner (m.milner@unb.ca)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

1) The end-user documentation included with the redistribution, if any, must include the following acknowledgment: "This product includes software developed by Matthew Milner for the Making Publics Project (http://www.makingpublics.org/) based at McGill University", in the same place and form as other third-party acknowledgments. Alternatively, this acknowledgment may appear in the software itself, or be presented through the application interface, in the same form and location as other such third-party acknowledgments.

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

 **********************************************************/
include("includes/phpheader.php");

if (! empty($_GET['page'])){
	$thispageid=$_GET['page'];
}else{
//Remove the str_replace in the production version.
$fullURI=$_SERVER['REQUEST_URI'];
$getmark=strpos($fullURI, "?");
if ($getmark) {
	$URI=substr($fullURI, 0, $getmark);
}else{
	$URI=$fullURI;
}

$trap=false;
if(strpos(trim($URI,"/"),'blog')===0){
//has to be START of URI!
	$trap='blog';
}elseif(strpos(trim($URI,"/"),'calendar')===0){
	$trap='calendar';
}elseif(strpos(trim($URI,"/"),'collections')===0){
	$trap='collections';
}elseif(strpos(trim($URI,"/"),'textiles')===0){
	$trap='textiles';
}elseif(strpos(trim($URI,"/"),'community')===0){
	$trap='community';
}
if($trap){
	//find url without trap
	$thispost=$trap;
	$thisurl=str_replace("/".$trap."/","",$URI);
	if($trap=='calendar' || $trap=='blog'){
		if(preg_match("/\/".$trap."\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*\//", $URI)){
			//preg match /yyyy/mm/dd/NNN/ FIVE slashes = find ID for post
			$findpost=explode("/",trim($thisurl,"/"));
			$thistitle=preg_replace("/[^a-z0-9]/i", "-", array_pop($findpost));
			$findpost=grabinfo('cms_'.$thispost,$thispost.'titlehash',sha1($thistitle),'1');
			$thispostid=$findpost[$thispost.'id'];
		}elseif(preg_match("/\/".$trap."\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\//", $URI)){
			//preg match /yyyy/mm/dd/ FOUR slashes = find day match
			$thispostrange[0]=str_replace("/","-",trim($thisurl,"/"))." 00:00:00";
			$thispostrange[1]=str_replace("/","-",trim($thisurl,"/"))." 23:59:59";
		}elseif(preg_match("/\/".$trap."\/[0-9]{4}\/[0-9]{2}\//", $URI)){
			$date=explode("/",trim($thisurl,"/"));
			$num = cal_days_in_month(CAL_GREGORIAN, $date[1], $date[0]);
			//preg match /yyyy/mm/ THREE slashes = find month match
			$thispostrange[0]=$date[0]."-".$date[1]."-01 00:00:00";
			$thispostrange[1]=$date[0]."-".$date[1]."-".$num." 23:59:59";
		}elseif(preg_match("/\/".$trap."\/[0-9]{4}\//", $URI)){
			$date=trim($thisurl,"/");

			//preg match /yyyy/ TWO slashes = find year match
			$thispostrange[0]=$date."-01-01 00:00:00";
			$thispostrange[1]=$date."-12-31 23:59:59";
		}
		//for textiles using VT_Tracking
	}elseif(preg_match("/\/".$trap."\/[A-Z]{4}[0-9]{2}[A-Z]{1}[0-9]{4}-[A-Z]/", $URI)){
		$findpage=trim($thisurl,"/");
		//transform VT_tracking into more useful field
		if($trap=='textiles'){
			$fixedvttracking=str_replace("-","%",$findpage);
			$grabtextile=mysql_query("select * from Textile where VT_tracking like '".$fixedvttracking."' limit 0,1",$oadbcon);
			if(mysql_num_rows($grabtextile)){
				$thistextile=mysql_fetch_assoc($grabtextile);
				$findid=$thistextile['Textile_id'];
			}
		}
	}elseif(preg_match("/\/".$trap."\/[0-9]*\//", $URI)){
		$findid=trim($thisurl,"/");
	}elseif(preg_match("/\/".$trap."\/.*\//", $URI)){
		$findpage=strtolower(trim($thisurl,"/"));
	}
	$URI="/".$trap."/";
}

//remove slashes AFTER ? is found!
$URI=remove_trailing_slash(remove_leading_slash($URI));

if (! empty($URI)) {
	//Search for a URL with zero or more leading or trailing /'s
	$pagefind=mysql_query("SELECT content_id FROM cms_contentsinfo WHERE content_url='/".$URI."' limit 0,1");
	if (mysql_num_rows($pagefind)) {
		$fetch=mysql_fetch_assoc($pagefind);
		$thispageid=$fetch['content_id'];
	}
}
}
/*if ($_SESSION['profile'] >= 6) {
	print "URI: '".$URI."'";
	print "<br />Page id: ".$thispageid;
}*/
if (empty($thispageid)) $thispageid=1;
//include($thisabspath."/frame/pieces/functions.php");
//user session / history login

$thispageinfo=makepage($thispageid);

if($log){
	if($_SESSION['user']){
		$user=$_SESSION['user'];
		mysql_query ( "update cms_users set lastactive=NOW() where userid='" . $user. "'" );
	}else{
		$user='anon';
	}
	sessionlog($user,$_SESSION['currentpage'],$thispageinfo['contenttitle'],'view');
		//only log pages with querystrings - others are plain content, no IP issues.
	$thisquery=$_SERVER['QUERY_STRING'];
	$ip4=$_SERVER['REMOTE_ADDR'];
	$thisip=iplog($user,$thispageid,$thisquery,$_SERVER['HTTP_REFERER'],$_SERVER['HTTP_USER_AGENT'],$ip4);

}

header('Content-Type: text/html; charset=utf-8');
$_SESSION['currentpage']="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="description"
	content="<?php print strip_tags($site_info['vreabout']);?>" />
<meta name="keywords"
	content="<?php
	print $site_info['vrecorecloud'];
	if (! empty ( $thispaginfo ['meta'] )) {
		print $thispaginfo ['meta'];
	}
	?>" />
<title><?php echo $site_name; ?> - <?php print $thispageinfo['contenttitle'];?></title>
<?php
//Site-wide css / js
include ($thisabspath."/includes/cssheader.php");
include ($thisabspath."/includes/javaheader.php");

//If there is a css file specific to this page, load it
if (! empty ( $thispageinfo ['cssfiles'] )) {
	print $thispageinfo ['cssfiles'];
}

//If there is a js file specific to this page, load it
if (! empty ( $thispageinfo ['jsfiles'] )) {
	print $thispageinfo ['jsfiles'];
}

//If there is css specific to this page, load it
if (! empty( $thispageinfo ['contentcss'])) {?>
<style>
	<?php print $thispageinfo ['contentcss'];?>
</style>
<?php }
//If there is js code specific to this page, load it
if($thispageinfo ['contentjs']) {?>
<script language="javascript" charset="utf-8">
// <![CDATA[
	<?php print eval ( $thispageinfo ['contentjs'] );?>
// ]]>
</script>
<?php } ?>
</head>
<body id="pagebody" class="not-front logged-in page-user ">
<div id="top-wrapper" class="clear-block">
<h1 id="top-title"><?php echo $site_name; ?></h1>
<span id="top"></span>
<?php include($thisabspath."/includes/mainmenu.php"); ?>
</div> <!--/top-wrapper-->

  <div id="middle-wrapper" class="clear-block" style="position:relative;">
  <div id="middle-inner" class="clear-block">
  <div id="middle-content" class="section wide clear-block">
 <div id="main-content" class="column" >
    <div class="content-inner clear-block " id="thispagecontents" style="min-height:400px;">
<?php //This is the content pulled from the DB
	print eval($thispageinfo['content']);?>

<?php if (empty($thispageinfo)) { ?>
	<h2>404: Page Not Found</h2>
<?php
}?>
<?php include($thisabspath."/includes/footer.php");
//rem'd as per discussion;
//makefooter($_SESSION['profile'], $thispageid);?>
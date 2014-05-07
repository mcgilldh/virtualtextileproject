<?php /*
* this page requires complete revision and trimming. many functions do NOT pertain to this site. need the following:
* querybuilder - grabinfo() work?
*
*
* bottom contains utility functions drawn from online libraries / code snippets etc.
*



FULL LIST of FUNCTIONs
******************************Content Management Functions*************************************

function makekeywords($type,$id,$link=TRUE)
function addpage()
function makecrumbs()
function countcrumbs()
function sitesection()
function findquerystring($mixed)
function makeresultscounter($s,$wallfor,$wallid)
function buildpageinfo($id)
function getpageinfo($id)
function makepage($id)
function chkpage($id)
function chkuserhash($hash)
function cleanplacestring($place)
function makecomment($findcomment)
function shortcomment($findthiscomment, $asarray = FALSE,$long=FALSE)
function docsbuild($doc,$length)
function builduserinfo($userid)
function networknamebuild($link,$networkid,$short,$c)
function usernamebuild($link,$usernameid,$org)
function discussionbuild($link,$fid)
function organizationbuild($link,$orgid)
function librarybuild($link,$libid)
function projectbuild($link,$pid)
function notebuild($link,$nid)

******************************Common Archive Data Functions*************************************

function chkformatcache($data,$dataid)
function thingbuild($link,$thingid)
function shortthingbuild($thingid,$link=FALSE)
function agentbuild($link,$personnameid,$format)
function findhas($thingid,$persontype)
function placenamebuild($link,$placeid,$long)
function eventnamebuild($link,$eventid)
function titlebuild($link,$title,$em,$thingid)
function collectionbuild($link,$title,$em)
function journalbuild($link,$em,$journalid)
function personnamebuild($link,$personnameid,$org)
function peopletypecount($type,$thingid)
function peopletypebuild($type,$thingid)
function getrandomthing()
function findwritertype($authorid,$authorname)
function buildplaceinfo($placeid)
function buildorginfo($orgid)
function buildjournalinfo($journalid)
function buildnoteinfo($noteid)
function getuserperson($userid)
function buildlistinfo($listid)
function buildclusterinfo($clusterid)
function buildprojectinfo($projectid)
function builddiscussioninfo($discussionid)
function buildeventinfo($eventid)
function buildthinginfo($thingid)
function buildpersoninfo($personid)
function crunchpeople($str,$fa)
function eventinfobuild($eventid)
function shorteventbuild($eventid)
function entitybuild($link,$entityid)
function eventbuild($link,$relid)
function inverseevent($n1,$n2,$t)
function buildeventinfo($relid)
function authorchk($person)
function searchworldcat($thingid)
function organizationauthorcheck($person)
function chkplaces($theseplaces)
function chkjournals($journals)
function chkorg($org)
function classbuildinfo($id)

******************************UTILITY FUNCTIONS*************************************

function formatBytes($bytes, $precision = 2)
function listing($items, $and = ' and ')
function getjpegsize($img_loc)
function is_odd($num)
function closetags($html)
function countpdfpages($file)
function greaterDate($start_date,$end_date)
function array_remove_keys($array, $keys = array())
function strstrb($h,$n)
function exists($u)
function r_implode( $glue, $pieces)
function strpos_array($haystack, $needles)
function create_time_range($start, $end, $by='5 mins')
function _make_url_clickable_cb($matches)
function _make_web_ftp_clickable_cb($matches)
function _make_email_clickable_cb($matches)
function make_clickable($ret)
function do_post_request($url, $data, $optional_headers = null)
function convert_smart_quotes($string)
function array_iunique($array)
function getContents($url)
function cleancurly($text)

END OF LIST
*/

/* ******************************Content Management Functions************************************* */


//THESE FUNCTIONS SHOULD BE SORTED AS PER YOUR NEW SYSTEM
//Grab results from any table using simple query, return as array with set number of values, or all.
function grabinfo($table,$idkey,$id,$n=false){
	if(!is_numeric($id)){
		$id="'".mysql_real_escape_string($id)."'";
	}
	if($n){
		$grabinfosql="select * from ".$table." where ".$idkey."=".$id." limit 0,".$n;
	}else{
		$grabinfosql="select * from ".$table." where ".$idkey."=".$id;
	}

	$grabinfo=mysql_query($grabinfosql);
	if(mysql_num_rows($grabinfo)){
		switch($n){
			case '1':
				$thisinfo=mysql_fetch_assoc($grabinfo);
				break;
			case false:
			default:
				while($thisrow=mysql_fetch_assoc($grabinfo)){
					$thisinfo[]=$thisrow;
				}
		}
		return $thisinfo;
	}else{
		return false;
	}
}

//Likely can get rid of this for mysql_last_id();

function grabnewestid($table,$idkey){
	$grabsql="select max(".$idkey.") as newid from ".$table." limit 0,1";
	$grabinfo=mysql_query($grabsql);
	if(mysql_num_rows($grabinfo)){
		$thisinfo=mysql_fetch_assoc($grabinfo);
		return $thisinfo['newid'];
	}else{
		return false;
	}
}

//find create a comment wall drawing on makecomment();
function makewall($cf,$cfi,$n='10'){
	$sql="select distinct commentid from comments where commentfortype='".$cf."' and commentforid=".$cfi." order by commentmadeon desc limit 0,".$n;
	$getcomments=mysql_query($sql);
	if(mysql_num_rows($getcomments)){
		while($getcommentrows=mysql_fetch_assoc($getcomments)){
			print makecomment($getcommentrows['commentid']);
		}
	}
}

/* Replace the old function with this one in functions.php
function makecomment($id){
	$thiscomment=grabinfo('comments','commentid',$id,'1');
	$thiscommenter=grabinfo('users','userid',$thiscomment['commentmadeby'],'1');
	$thisdatetime=date('M j, Y \a\t g:ia',strtotime($thiscomment['commentmadeon']));?>
<div id="comment<?php print $thiscomment['commentid'];?>block" class="comment clearfix">
<div id="comment<?php print $thiscomment['commentid'];?>footer" class="commentfoot clearfix">
<strong><?php if($_SESSION['profile']<=3 && $thiscommenter['nickname']){
print $thiscommenter['nickname'];
}else{
print $thiscomment['commentmadeby'];
if($thiscommenter['nickname']){
	print "(".$thiscommenter['nickname'].")";
	}
}?></strong> &#183; <?php print $thisdatetime;
if($thiscomment['commentmadeby']==$_SESSION['user']){?>
<a href="/~matthew.milner/f/data/modal/comments.php?<?php print $thiscomment['commentfortype'];?>=<?php print $thiscomment['commentid'];?>&j=e" class="right_this popmodal"><img title="edit" src="/~matthew.milner/f/img/icons/edit.png" class="icon"></a><?php
}?></div>
<div id="comment<?php print $thiscomment['commentid'];?>" class="commentmain clearfix">
<?php print make_clickable(stripslashes(nl2br($thiscomment['comment'])));?>
</div>
</div><?php
}
*/

// grabs all page content info for CMS; returns as array
// OK for new DB
function buildpageinfo($id) {
	$findpagecontents = mysql_query ( "select * from cms_contents inner join cms_contentsinfo on cms_contents.content_id=cms_contentsinfo.content_id where cms_contents.content_id=" . $id . " limit 0,1" );
	if (mysql_num_rows ( $findpagecontents )) {
		$pageinfo = mysql_fetch_assoc ( $findpagecontents );
	}
	return $pageinfo;
}

function get_versions($id) {
	$query="select content_unique,content_for from cms_contents where content_id=".$id." order by content_for desc";
	$results=mysqli_query($query);
	$versions=array();
	while ($rows=mysqli_fetch_assoc($results)) {
		$versions[$rows['content_for']] = $rows['content_unique'];
	}
	return $versions;
}

//has to be content_unique
function getpageparts($unique) {
	if ($_SESSION['profile'] >=4) {
		$findpagecontentssql = "select * from cms_contents inner join cms_contentsinfo on cms_contents.content_id=cms_contentsinfo.content_id where cms_contents.content_unique=" . $unique . " limit 0,1";

		$findpagecontents = mysql_query ( $findpagecontentssql );
		echo mysql_error();
		if (empty ( $findpagecontents )) {
			$editpageinfo ['content'] = 'There is no page with this id.';
		} else {
			while ( $thispagerow = mysql_fetch_assoc ( $findpagecontents ) ) {
				$editpageinfo ['content'] = stripslashes ( $thispagerow ['content'] );
				$editpageinfo ['contentjs'] = stripslashes ( $thispagerow ['content_js'] );
				$editpageinfo ['contentjsfiles'] = $thispagerow ['content_jsfiles'];
				$editpageinfo ['contentcss'] = $thispagerow ['content_css'];
				$editpageinfo ['contentcssfiles'] = $thispagerow ['content_cssfiles'];
				$editpageinfo ['contenttitle'] = stripslashes ( $thispagerow ['content_title'] );
				$editpageinfo ['contentmadeby'] = $thispagerow ['content_madeby'];
				$editpageinfo ['contentfor'] = $thispagerow ['content_for'];
				$editpageinfo ['contentmoddate'] = $thispagerow ['content_moddate'];
				$editpageinfo ['contentunique'] = $thispagerow ['content_unique'];
				$editpageinfo ['contenttags'] = makekeywords ( 'page', $id, FALSE );
				$editpageinfo ['contenturl'] = remove_leading_slash($thispagerow['content_url']);
			}
		}
	}
	return $editpageinfo;
}

//Return a string with no leading slashes
function remove_leading_slash($input) {
	while (substr($input,0,1) == "/") $input = substr($input,1);
	return $input;
}

//Return a string with no trailing slashes
function remove_trailing_slash($input) {
	while (substr($input,-1) == "/") $input = substr($input,0,-1);
	return $input;
}

/*
 * Takes an access level and prints a user readable string
 */
function access_to_string($access) {
	switch ($access) {
		case NULL:
			return "Open to All";
			break;
		case "1":
			return "Guests";
			break;
		case "2":
			return "Community Members";
			break;
		case "3":
			return "Academic Members";
			break;
		case "4":
			return "Project Members";
			break;
		case "5":
			return "Project Admins";
			break;
		case "6":
			return "Site Admins";
			break;
		case "7":
			return "Master";
			break;
	}
}

function addpage($title, $tags, $pagelock) {
	$pagetitle = "'" . mysql_real_escape_string($title) . "'";
	$pagetags = "'" . mysql_real_escape_string($tags) . "'";

	if ($pagelock == 'yes') {
		$pagelock = 1;
	} else {
		$pagelock = 0;
	}

	$revision = 1;

	$sqli = "Insert into cms_contentsinfo (content_title,content_lock) values (" . $pagetitle . "," . $pagelock . ")";
	mysql_unbuffered_query($sqli);

	if (mysql_errno() != 0) {
		$titlesuccess = FALSE;
	} else {
		$titlesuccess = TRUE;
	}

	if ($titlesuccess) {
		$getnewid = mysql_query('select content_id from cms_contentsinfo order by content_id desc limit 0,1');
		while ($getnewidrows = mysql_fetch_assoc($getnewid)) {
			$newid = $getnewidrows['content_id'];

			if ($tags) {
				$deletesql = "delete from cms_keywords WHERE linkid=" . $newid . " and linktype='page'";
				mysql_query($deletesql);
				$keywordarray = explode(',', mysql_real_escape_string(cleancurly($tags)));
				foreach ($keywordarray as $newkeyword) {
					$newkeyword = trim($newkeyword);
					if ($newkeyword != NULL) {
						if (!ctype_punct($newkeyword) && strlen($newkeyword) >= 2) {
							if ($newkeyword != "" || $newkeyword != " ") {
								$sql = "INSERT INTO cms_keywords (linkid,keyword,linktype)";
								$sql = $sql . " VALUES ";
								$sql = $sql . "(" . $newid . ",'" . $newkeyword . "','page')";
								mysql_query($sql);
							}
						}
					}
				}
			}

		}
		$pageresults['status'] = 'ok';
		$pageresults['id'] = $newid;
		$pageresults['title'] = $pagetitle;
	} else {
		$pageresults['status'] = 'no';
	}
	return $pageresults;
}


//Removes script tags, escapes quotes, and gets content ready for the database.
function sanitize_content($input) {
	$trimmed_input = trim($input);
	if (! empty ( $trimmed_input )) {
		$output= str_replace ( "<script", "<scr@%ipt", $trimmed_input );
		$output = str_replace ( "</script>", "</scr@%ipt>", $output );
		$output = str_replace ( "<textarea", "<text@%area", $output );
		$output = str_replace ( "</textarea>", "</text@%area>", $output );
		$output = "'" . mysql_real_escape_string ( $output ) . "'";
		$output = htmlspecialchars_decode($output);
	} else {
		$output = "NULL";
	}
	return $output;
}

// grabs page header info only; returns as array
// OK for new DB
function getpageinfo($id) {
	$getpage = mysql_query ( "select * from cms_contentsinfo where content_id=" . $id . " limit 0,1" );
	if (mysql_num_rows ( $getpage )) {
		while ( $pageinforows = mysql_fetch_assoc ( $getpage ) ) {
			$pageinfo ['url'] = $pageinforows ['content_url'];
			$pageinfo ['title'] = $pageinforows ['content_title'];
			$pageinfo ['tags'] = $pageinforows ['content_tags'];
		}
	} else {
		$pageinfo = 'no page';
	}
	return $pageinfo;
}

// grabs page contents per ID and Profile. Checks if page exists, if content exists. Parses script tags and php eval code. returns as array to populate includes/maintemplate.php
// OK for new DB
function makepage($id) {
	if(chkpage ( $id )){
	if ($_SESSION ['user']) {
		if ($_SESSION['profile'] >= 3 && !empty($_REQUEST['content_for'])) {
			$thisaccess = $_REQUEST['content_for'];
		}
		else $thisaccess = $_SESSION ['profile'];
	}
	if (! empty($thisaccess) && $thisaccess != "NULL") {
		$query="select * from cms_contents inner join cms_contentsinfo on cms_contents.content_id=cms_contentsinfo.content_id where cms_contents.content_id=" . $id . " and (cms_contents.content_for<=" . $thisaccess . " or cms_contents.content_for is NULL) order by cms_contents.content_for desc limit 0,1";
		$findpagecontents = mysql_query($query);
	} else {
		$findpagecontents = mysql_query("select cms_contents.content,cms_contents.content_css,cms_contents.content_js,cms_contents.content_cssfiles,cms_contents.content_jsfiles,cms_contentsinfo.content_title,cms_contents.content_madeby,cms_contents.content_modby,date_format(cms_contents.content_moddate, '%b %e, %Y') as content_modified from cms_contents inner join cms_contentsinfo on cms_contents.content_id=cms_contentsinfo.content_id where cms_contents.content_id=" . $id . " and cms_contents.content_for is NULL limit 0,1" );
	}

	//print mysql_error();

	if (mysql_num_rows ( $findpagecontents )) {
		$thispageinfo ['content'] = '?>Either you do not have access rights to this page, or there is no page with this id.<?php ';
		while ( $thispagerow = mysql_fetch_assoc ( $findpagecontents ) ) {
			$thiscontentfix = str_replace ( "<scr@%ipt", "<script", $thispagerow ['content'] );
			$thiscontentfix = str_replace ( "</scr@%ipt>", "</script>", $thiscontentfix );
			$thiscontentfix = str_replace ( "<text@%area", "<textarea", $thiscontentfix );
			$thiscontentfix = str_replace ( "</text@%area>", "</textarea>", $thiscontentfix );
			$thispageinfo ['content'] = '?>' . stripslashes ( $thiscontentfix ) . '<?php ';
			if (! empty ( $thispagerow ['content_js'] )) {
				$thisjsfix = str_replace ( "<scr@%ipt", "<script", $thispagerow ['content_js'] );
				$thisjsfix = str_replace ( "</scr@%ipt>", "</script>", $thisjsfix );
				$thisjsfix = str_replace ( "<text@%area", "<textarea", $thisjsfix );
				$thisjsfix = str_replace ( "</text@%area>", "</textarea>", $thisjsfix );
				$thispageinfo ['contentjs'] = '?>' . stripslashes ( $thisjsfix ) . '<?php ';
			}else{
				$thispageinfo ['contentjs']=NULL;
			}
			$thispageinfo ['contentcss'] = stripslashes ( $thispagerow ['content_css'] );
			$thispageinfo ['contenttitle'] = stripslashes ( $thispagerow ['content_title'] );
			$thispageinfo ['contentmadeby'] = $thispagerow ['content_madeby'];
			$thispageinfo ['content_modified'] = $thispagerow ['content_moddate'];
			$thispageinfo ['content_modby'] = $thispagerow ['content_modby'];
			if (! empty($thispagerow['content_cssfiles']))
				$thispageinfo['cssfiles']="<link rel=\"stylesheet\" href=\"".str_replace(",","\" />\n<link rel=\"stylesheet\" href=\"",stripslashes($thispagerow['content_cssfiles']))."\" />";
			else $thispageinfo['cssfiles'] = "";
			if (! empty($thispagerow['content_jsfiles']))
				$thispageinfo['jsfiles']="<script src=\"".str_replace(",","\"></script>\n<script src=\"",stripslashes($thispagerow['content_jsfiles']))."\"></script>";
			else $thispageinfo['jsfiles'] = "";
		}
	} else {
		$thispageinfo = NULL;
		echo "No rows returned.";
		echo "<br />".$query;
	}
	}else{
		$thispageinfo=NULL;
	}
	return $thispageinfo;
}

// checks if page exists
// OK for new DB
function chkpage($id) {
	$findpagecontents = mysql_query ( "SELECT * FROM cms_contentsinfo WHERE content_id=" . $id . " LIMIT 0, 1" );
	if (mysql_num_rows ( $findpagecontents )) {
		$thispagecurrent = true;
	} else {
		$thispagecurrent = false;
	}
	return $thispagecurrent;
}

// Logs user's visit to page for history etc.
function sessionlog($user,$thisurl,$thistitle,$thisaction){
	$pagesql="INSERT INTO cms_sessioninfo (userid,page,title,thisaction)";
	$pagesql=$pagesql . " VALUES ";
	$pagesql=$pagesql . "('" . $user . "','";
	$pagesql=$pagesql . mysql_real_escape_string($thisurl) . "','".$thistitle."','".$thisaction."')";
	mysql_query($pagesql);
}

// Logs user's IP and measures time since last page request.
function iplog($user,$thispage,$thisquery,$thisreferer,$thisagent,$ip4=NULL,$ip6=NULL){
	$now=microtime(TRUE);
	if($ip6){
		$ip4='NULL';
		$grablast=mysql_query("select * from cms_ipmonitor where requestby='".$user."' and requestip6='".$ip6."' order by requestid desc limit 0,1");
	}else{
		$ip6='NULL';
		$grablast=mysql_query("select * from cms_ipmonitor where requestby='".$user."' and requestip4='".$ip4."' order by requestid desc limit 0,1");
	}
	if(mysql_num_rows($grablast)){
		$lastrequest=mysql_fetch_assoc($grablast);
		$thislasttime=$now-$lastrequest['requesttime'];
	}else{
		$thislasttime=0;
	}

	$lastrequest=grabnewestid('','requestid');
	$pagesql="INSERT INTO cms_ipmonitor(requestby,requestip4,requestip6,requestpage,requestquery,requestreferer,requestagent,requestresult,requesttime,timesincelast)";
	$pagesql=$pagesql . " VALUES ";
	$pagesql=$pagesql . "('" . $user . "','";
	$pagesql=$pagesql . $ip4 . "',".$ip6.",".$thispage.",'";
	$pagesql=$pagesql . $thisquery . "','".$thisreferer."','".$thisagent."',0,".$now.",".strval($thislasttime).")";
	mysql_query($pagesql);
}

/* ========NOT SORTED YET BELOW===== */
// Create array of tags linked to search. Usually coupled with function listing() for tag clouds.
//OK for new Db

function makekeywords($type, $id, $link = TRUE) {
	$keywordurlarray = array ();
	$keywords = mysql_query ( "SELECT * FROM cms_keywords where linkid=" . $id . " and linktype='" . $type . "' order by keyword asc" );
	if (mysql_num_rows ( $keywords )) {
		while ( $row = mysql_fetch_assoc ( $keywords ) ) {
			if ($link) {
				$keywordurlarray [] = "<a href='" . $cms_url . "/search/?kw=" . urlencode ( stripslashes ( ucwords ( $row ['keyword'] ) ) ) . "'>" . stripslashes ( $row ['keyword'] ) . "</a>";
			} else {
				$keywordurlarray [] = stripslashes ( ucwords ( $row ['keyword'] ) );
			}
		}
	}
	return $keywordurlarray;
}

// OK for new DB
function makeresultscounter($s, $wallfor, $wallid) {
	$chkwallfor = mysql_query ( "select userid from cms_users where userid='" . $wallfor . "'" );
	if (mysql_num_rows ( $chkwallfor )) {
		$findallcomments = mysql_query ( "SELECT * FROM cms_comments where userid='" . $_SESSION ['user'] . "'" );
	} else {
		$findallcomments = mysql_query ( "SELECT * FROM cms_comments where commentfor='" . $wallfor . "' and commentforid=" . $wallid . " and commentlock=0" );
	}
	$totalcomments = "";
	if (mysql_num_rows ( $findallcomments )) {
		$totalcomments = mysql_num_rows ( $findallcomments );
	}
	$totalpages = $totalcomments / $_SESSION ['userprefs'] ['commentdisplay'];
	if (! is_int ( $totalpages )) {
		$remainderpages = $totalpages - intval ( $totalpages );
		if ($remainderpages > 0) {
			$totalpages = intval ( $totalpages ) + 1;
		}
	}
	$position = $s / $_SESSION ['userprefs'] ['commentdisplay'];
	if (! is_int ( $position )) {
		$remainderposition = $position - intval ( $position );
		if ($remainderposition > 0) {
			$position = intval ( $position ) + 1;
		}
	}

	$resultscounter ['totalpages'] = $totalpages;
	$resultscounter ['position'] = $position;

	return $resultscounter;
}


// create comment as html / print each comment as html for wall / contains ajax edit scripts (prototype)
// OK for new DB
function makecomment($findcomment) {
	$result = mysql_query ( "SELECT *, date_format(commentmade, '%b %e, %Y') as commentmadedate, date_format(commentmade, '%k:%i') as commentmadetime FROM cms_comments where commentid=" . $findcomment );
	if (mysql_num_rows ( $result )) {
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			$thistitle = stripslashes ( $row ['commenttitle'] );
			$thiscomment = stripslashes ( nl2br ( $row ['commentinfo'] ) );
			$thisdate = $row ['commentmadedate'];
			$thistime = $row ['commentmadetime'];
			$thisuserid = $row ['userid'];
			$thiscommentid = $row ['commentid'];

			$commentblock = <<<EOD
<div class="wallcomment clear-block" id="comment{$thiscommentid}" name="comment{$thiscommentid}">
<div class="commentarea clear-block"><span id="comment{$thiscommentid}box" class="thiscomment clear-block">{$thiscomment}</span>
<p class="user clear-block">
EOD;

			if ($thisuserid == $_SESSION ['user']) {
				$commentblock = $commentblock . "Me";
			} else {
				$commentblock = $commentblock . usernamebuild ( "l", $thisuserid, "s" );
			}

			$commentblock = $commentblock . <<<EOD
</p>
</div>
<div class="commentadmin clear-block">
<span class="time left">{$thisdate} at {$thistime}</span>
<span class="right" id="commentadminctl{$thiscommentid}">
EOD;

			if (($_SESSION ['user'] == $thisuserid)) {
				$commentblock = $commentblock . <<<EOD
<img src="$cms_url/frame/images/icons/icons/editcomment.png" title="edit this comment" class="icon ajaxlink" onclick="editcomments('{$thiscommentid}');" id="comment{$thiscommentid}ctl"><img src="$cms_url/frame/images/icons/icons/delete.png" title="delete this comment" class="icon ajaxlink" onclick="deletecomment('{$thiscommentid}');">
EOD;
			}
			$commentblock = $commentblock . <<<EOD
<img src="$cms_url/frame/images/icons/icons/likeoff.png" id="commentlike{$thiscommentid}" class="icon ajaxlink" onclick="mark('comment','{$thiscommentid}');"><img src="$cms_url/frame/images/icons/icons/flag.png" id="commentflag{$thiscommentid}" title="is this comment offensive or does it contravene the code of conduct? alert the user and community administration" class="icon ajaxlink">
EOD;
			if ($_SESSION ['profile'] >= 2) {
				$commentblock = $commentblock . <<<EOD
<img src="$cms_url/frame/images/icons/icons/unlock.png" onclick="commentlock('{$thiscommentid}');" id="commentlock{$thiscommentid}" class="ajaxlink icon" title="lock this comment from view and send it for adjudication by peers">
EOD;
			}
			$commentblock = $commentblock . <<<EOD
</span></div>
<div id="commentdetailsshow{$thiscommentid}" onclick="showcommentdetails('{$thiscommentid}');" class="detailstoggle clear-block">show references & keywords...</div>
<div id="commentdetails{$thiscommentid}" style="display:none;" class="commentdetails clear-block">
<div id="keywordscomment{$thiscommentid}">
EOD;
			unset ( $commentkeywords );
			$commentkeywords = makekeywords ( 'comment', $thiscommentid );
			if (! empty ( $commentkeywords )) {
				$hastags = TRUE;
			} else {
				$hastags = FALSE;
			}
			if (count ( $commentkeywords ) == 0) {
				$addedit = "Add";
			} else {
				$addedit = "Edit";
			}

			if (! empty ( $_SESSION ['user'] )) {
				$commentblock = $commentblock . <<<EOD
<img src="$cms_url/frame/images/icons/icons/tag.png" class="left icon ajaxlink" onclick="editkeywords('comment','{$thiscommentid}');" title="{$addedit} Tags" id="keywordscomment{$thiscommentid}ctl">
EOD;
			}
			$commentblock = $commentblock . <<<EOD
<span id="keywordscomment{$thiscommentid}box">
EOD;
			if ($hastags) {
				$commentblock = $commentblock . listing ( $commentkeywords );
			}
			$commentblock = $commentblock . <<<EOD
</span></div>
EOD;

			$findassociatedentries = mysql_query ( "select linkid,notes from cms_associations where rootid=" . $thiscommentid . " and root='comment' and linkedtoitem='thing'" );
			if (mysql_num_rows ( $findassociatedentries )) {
				$commentblock = $commentblock . <<<EOD
	 <span style="font-size:0.85em;font-weight:bold;">References</span><br />
     <ul style="font-size:0.85em;list-style:square;">
EOD;
				while ( $findassociatedentriesrow = mysql_fetch_assoc ( $findassociatedentries ) ) {
					$commentblock = $commentblock . "<li>&#187;" . thingbuild ( "e", $findassociatedentriesrow ['linkid'] );
					if ($findassociatedentriesrow ['notes']) {
						$commentblock = $commentblock . " [" . $findassociatedentriesrow ['notes'] . "]</li>";
					} else {
						$commentblock = $commentblock . "</li>";
					}
				}
				$commentblock = $commentblock . "</ul>";
			}
			$commentblock = $commentblock . <<<EOD
	 </div>
EOD;
			if (($_SESSION ['user'] == $thisuserid)) {
				$commentblock = $commentblock . <<<EOD

EOD;
			}
			$commentblock = $commentblock . "</div>";
		}
	}
	return $commentblock;
}

// Get all data related to comment, unformatted.
function getcomment($id) {
	$commentsearch = mysql_query ( "SELECT * FROM cms_comments WHERE commentid=" . $id . " limit 0,1" );
	if (mysql_num_rows ( $commentsearch )) {
		while ( $commentsearchrow = mysql_fetch_assoc ( $commentsearch ) ) {
			return $commentsearchrow;
		}
	} else {
		return "no such comment exists";
	}
}

// Build link for document stored in CMS / with mime-ICONs
function docsbuild($doc, $length) {
	// OK for new DB
	$docfind = mysql_query ( "select * from cms_docs where docid=" . $doc . " limit 0,1" );
	$docrow = mysql_fetch_assoc ( $docfind );
	$mimetype = $docrow ['docmime'];
	$realpath = trim ( $docrow ['docpath'] );
	$httppath = "$cms_url/documents/doc.php?id=" . $doc;
	$filebytes = filesize ( $realpath );
	$fileext = pathinfo ( $realpath, PATHINFO_EXTENSION );
	$filesize = "(" . formatBytes ( $filebytes ) . ") ";

	$pathparts = Explode ( '/', $realpath );
	$filename = $pathparts [count ( $pathparts ) - 1];

	if ($length == "s") {
		$filesize = $docrow ['doctitle'] . " " . $filesize;
	}
	switch ($mimetype) {
		case "image/jpeg" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/image.png" title="' . $filesize . ' ' . $pixels . '" class="icon" />';
			$endinfo = $pixels;
			break;
		case "audio/mp4" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/mp4.png" title="' . $filesize . '" class="icon" />';
			break;
		case "video/mpeg" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/mpeg.png" title="' . $filesize . '" class="icon" />';
		case "video/avi" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/avi.png" title="' . $filesize . '" class="icon" />';
			break;
		case "image/gif" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/image.png" title="' . $filesize . '" class="icon" />';
			break;
		case "image/png" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/image.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/pdf" :
			$filesize = "PDF " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/pdf.png" title="' . $filesize . ' ' . $numberofpages . '" class="icon" />';
			$endinfo = $numberofpages;
			break;
		case "application/msword" :
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document" :
			$filesize = "MS Word " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/word.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/vnd.ms-powerpoint" :
		case "application/vnd.openxmlformats-officedocument.presentationml.presentation" :
			$filesize = "MS Powerpoint " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/ppt.png" title="' . $filesize . '" class="icon" />';
			break;
		case "audio/mpeg" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/mp3.png" title="' . $filesize . '" class="icon" />';
			break;
		case "text/html" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/html.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/rtf" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/rtf.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/vnd.oasis.opendocument.text" :
			$filesize = "OpenOffice " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/odt.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/vnd.oasis.opendocument.spreadsheet" :
			$filesize = "OpenOffice " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/ods.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/vnd.oasis.opendocument.presentation" :
			$filesize = "OpenOffice " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/odp.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/vnd.oasis.opendocument.graphics" :
			$filesize = "OpenOffice " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/odg.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/vnd.oasis.opendocument.formula" :
			$filesize = "OpenOffice " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/odf.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/vnd.ms-excel" :
		case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" :
			$filesize = "MS Excel " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/xls.png" title="' . $filesize . '" class="icon" />';
			break;
		case "text/plain" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/text.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/zip" :
		case "application/x-gzip" :
			$filesize = "Zip/GZip " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/zip.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/ogg" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/ogg.png" title="' . $filesize . '" class="icon" />';
			break;
		case "image/tiff" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/tiff.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/x-rar-compressed" :
			$filesize = "RAR " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/rar.png" title="' . $filesize . '" class="icon" />';
			break;
		case "application/x-tar" :
			$filesize = "TAR " . $filesize;
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/tar.png" title="' . $filesize . '" class="icon" />';
			break;
		case "audio/x-ms-wma" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/wma.png" title="' . $filesize . '" class="icon" />';
			break;
		case "text/xml" :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/xml.png" title="' . $filesize . '" class="icon" />';
			break;
		default :
			$iconpath = '<img src="$cms_url/frame/images/icons/mime/doc.png" title="' . $filesize . '" class="icon" />';
			break;
	}

	switch ($length) {
		case "t" :
			$docline = $docrow ['doctitle'];
			break;
		case "s" :
			$docline = "<a target='_blank' href='" . $httppath . "' title='" . stripslashes ( $docrow ['doctitle'] ) . "' id='docico" . $doc . "'>" . $iconpath . "</a>";
			break;
		case "l" :
			$docline = "<a target='_blank' href='" . $httppath . "' title='" . stripslashes ( $docrow ['doctitle'] ) . "' id='docico" . $doc . "'>" . $iconpath . " " . $docrow ['doctitle'] . "</a>";
			break;
		case "f" :
			$docline = "<a target='_blank' href='" . $httppath . "' title='" . stripslashes ( $docrow ['doctitle'] ) . "' id='docico" . $doc . "'>" . $iconpath . " " . $docrow ['doctitle'] . "</a><span class='blogdate'>";
			if ($filesize) {
				$docline = $docline . " (" . $filename . " - " . $filesize . ")";
			}
			if ($fileext == "jpg" || $fileext == "jpeg") {
				if (getjpegsize ( $realpath )) {
					$docline = $docline . " " . implode ( "x", getjpegsize ( $realpath ) ) . " pixels";
				}
			}
			if ($fileext == "pdf") {
				$num = countpdfpages ( $realpath );
				if ($num == 1) {
					$pageword = " page";
				} else {
					$pageword = " pages";
				}
				$docline = $docline . " " . $num . $pageword;
			}
			$docline = $docline . "</span>";
			break;
	}
	return $docline;
}

// Grab all user info and return as array
// OK for new DB
function builduserinfo($userid) {
	$rsuser = mysql_query ( "select * from cms_users inner join cms_userpreferences on cms_users.userid=cms_userpreferences.userid where cms_users.userid='" . $userid . "' and preferenceset='full' limit 0,1" );
	while ( $row = mysql_fetch_assoc ( $rsuser ) ) {
		$userinfo ['personid'] = stripslashes ( $row ['personid'] );
		$userinfo ['password'] = stripslashes ( $row ['password'] );
		$userinfo ['lastname'] = stripslashes ( $row ['lastname'] );
		$userinfo ['firstname'] = stripslashes ( $row ['firstname'] );
		$userinfo ['middlename'] = stripslashes ( $row ['middlename'] );
		$userinfo ['email'] = stripslashes ( $row ['email'] );
		$userinfo ['office'] = stripslashes ( $row ['officeaddress'] );
		$userinfo ['phone'] = stripslashes ( $row ['phone'] );
		$userinfo ['organization'] = $row ['organization'];
		$userinfo ['department'] = stripslashes ( $row ['department'] );
		$userinfo ['citationstyle'] = stripslashes ( $row ['citationstyle'] );
		$userinfo ['styletype'] = stripslashes ( $row ['styletype'] );
		$userinfo ['defaulthome'] = stripslashes ( $row ['defaulthome'] );
		$userinfo ['favlibrary'] = stripslashes ( $row ['favlibrary'] );
		$userinfo ['favcatalogue'] = stripslashes ( $row ['favcatalogue'] );
		$userinfo ['filefolder'] = stripslashes ( $row ['filefolder'] );
		$userinfo ['defaultbrowser'] = stripslashes ( $row ['defaultbrowser'] );
		$userinfo ['faceimage'] = stripslashes ( $row ['faceimage'] );
		$userinfo ['connectionproxy'] = stripslashes ( $row ['connectionproxy'] );
		$userinfo ['proxyport'] = stripslashes ( $row ['proxyport'] );
		$userinfo ['proxyid'] = stripslashes ( $row ['proxyid'] );
		$userinfo ['proxypwd'] = stripslashes ( $row ['proxypwd'] );
		$userinfo ['emaildigest'] = stripslashes ( $row ['emaildigest'] );
		$userinfo ['emaildirect'] = stripslashes ( $row ['emaildirect'] );

		$userinfo ['place'] = stripslashes ( $row ['place'] );
		$userinfo ['place'] = $row ['place'];
		$userinfo ['country'] = stripslashes ( $row ['country'] );
		$userinfo ['zippostalcode'] = stripslashes ( $row ['zippostalcode'] );
		$userinfo ['profile'] = stripslashes ( $row ['profile'] );
		$userinfo ['lockid'] = stripslashes ( $row ['lockid'] );
		$userinfo ['commentdisplay'] = stripslashes ( $row ['commentdisplay'] );
		$userinfo ['agree'] = stripslashes ( $row ['agree'] );
		$userinfo ['userinterview'] = stripslashes ( $row ['userinterview'] );
		$userinfo ['usernotes'] = stripslashes ( $row ['usernotes'] );
		$userinfo ['startdate'] = stripslashes ( $row ['startdate'] );
		$userinfo ['enddate'] = stripslashes ( $row ['enddate'] );
		$userinfo ['pwddate'] = stripslashes ( $row ['pwddate'] );
		$userinfo ['preferenceset'] = stripslashes ( $row ['preferenceset'] );
		$userinfo ['cloud'] = stripslashes ( $row ['cloud'] );
		$userinfo ['dashboardon'] = stripslashes ( $row ['dashboardon'] );
		$userinfo ['filefolder'] = stripslashes ( $row ['filefolder'] );
		$userinfo ['userid'] = stripslashes ( $row ['userid'] );
	}

	if (! empty ( $userinfo ['organization'] )) {
		$findorganization = mysql_query ( "SELECT * FROM organizations WHERE organizationid=" . $userinfo ['organization'] );
		if (mysql_num_rows ( $findorganization )) {
			while ( $findorganizationrow = mysql_fetch_assoc ( $findorganization ) ) {
				$userinfo ['organizationname'] = $findorganizationrow ['organizationname'];
			}
		}
	} else {
		$userinfo ['organizationname'] = NULL;
	}

	if (! empty ( $userinfo ['place'] )) {
		$findplace = mysql_query ( "SELECT * FROM places WHERE placeid=" . $userinfo ['place'] );
		if (mysql_num_rows ( $findplace )) {
			while ( $findplacerow = mysql_fetch_assoc ( $findplace ) ) {
				$userinfo ['placename'] = $findplacerow ['place'];
			}
		}
	} else {
		$userinfo ['placename'] = NULL;
	}

	return $userinfo;
}

// Return username text/link
function usernamebuild($link, $usernameid, $org) {
	$usernameinfo = mysql_query ( "SELECT lastname,middlename,firstname,userid,personid,organization FROM cms_users WHERE userid='" . $usernameid . "' LIMIT 0, 1" );
	while ( $rowusername = mysql_fetch_assoc ( $usernameinfo ) ) {
		$username = stripslashes ( $rowusername ['firstname'] ) . " ";
		if (! empty ( $rowusername ['middlename'] )) {
			$username = $username . ($rowusername ['middlename']) . " ";
		}
		$username = $username . stripslashes ( $rowusername ['lastname'] );
		$thisorg = $rowusername ['organization'];
		$thisuserpersonid = $rowusername ['personid'];
	}

	/*
	 * THIS NEEDS TO BE FIXED! $rsorg = mysql_query("SELECT * FROM organizations join othernames on othernames.nameidwhere organizationid=" . $thisorg. " limit 0,1"); if (empty($rsorg)){ $thisorganizationname=""; } else{ while($roworg = mysql_fetch_assoc($rsorg)){ $thisabbr=$roworg['organizationabbr']; } switch($org){ case "l": $thisorganizationname=" (".$thisabbr.")"; break; case "s": $thisorganizationname=""; break; } }
	 */
	$thisorganizationname = "";
	if ($link == 'l') {
		return "<a href='$cms_url/archive/people/?id=" . $thisuserpersonid . "'>" . $username . "</a>" . $thisorganizationname;
	} else {
		return $username . $thisorganizationname;
	}
}

// Return discussion text/link
function discussionbuild($link, $discussionid) {
	$discussioninfo = mysql_query ( "SELECT * FROM cms_discussions WHERE discussionid=" . $discussionid . " LIMIT 0, 1" );
	if (mysql_num_rows ( $discussioninfo )) {
		while ( $discussionrow = mysql_fetch_assoc ( $discussioninfo ) ) {
			$discussionname = stripslashes ( $discussionrow ['discussiontitle'] );
			$discussiontype = stripslashes ( $discussionrow ['discussiontype'] );
		}
		if ($link == "l") {
			$discussionname = "<a href='$cms_url/discussions/?id=" . $discussionid . "'>" . $discussionname . "</a>";
		}
		return $discussionname;
	}
}

// Return organization text/link
function organizationbuild($link, $orgid) {
	$thisorganization = chkformatcache ( 'organization', $orgid, 'organizationname' );
	if ($thisorganization) {
		if ($link == "l") {
			$thisorganization = "<a href='$cms_url/archive/corporations/?id=" . $orgid . "'>" . $thisorganization . "</a>";
		}
		return $thisorganization;
	} else {
		$organizationinfo = mysql_query ( "SELECT * FROM organizations WHERE organizationid=" . $orgid . " LIMIT 0, 1" );
		if (mysql_num_rows ( $organizationinfo )) {
			$organizationrow = mysql_fetch_assoc ( $organizationinfo );
			$organizationname = stripslashes ( $organizationrow ['organizationname'] );
			$thisorganization = htmlentities ( $organizationname, ENT_QUOTES, "UTF-8" );
			mysql_query ( "delete from formatedcache where datatype='organization' and dataid=" . $orgid . " and formatstyle='organizationname'" );
			mysql_query ( "insert into formatedcache (datatype,dataid,formatstyle,formatedtext) values ('organization'," . $orgid . ",'organizationname','" . mysql_real_escape_string ( $thisorganization ) . "')" );
			if ($link == "l") {
				$organizationname = "<a href='$cms_url/archive/corporations/?id=" . $orgid . "'>" . $organizationname . "</a>";
			}
			return $organizationname;
		}
	}
}

// Return library text/link
function librarybuild($link, $libid) {
	$libraryinfo = mysql_query ( "SELECT * FROM tbllibrary WHERE libraryid=" . $libid . " LIMIT 0, 1" );
	while ( $libraryrow = mysql_fetch_assoc ( $libraryinfo ) ) {
		$libraryname = stripslashes ( $libraryrow ['libraryname'] );
	}
	if ($link == "l") {
		$libraryname = "<a href='$cms_url/corporations/?id=" . $libid . "'>" . $libraryname . "</a>";
	}
	return $libraryname;
}

// Return user project text/link
function projectbuild($link, $pid) {
	$projectinfo = mysql_query ( "SELECT * FROM cms_projects WHERE projectid=" . $pid . " LIMIT 0, 1" );
	while ( $projectrow = mysql_fetch_assoc ( $projectinfo ) ) {
		$projectname = stripslashes ( $projectrow ['projecttitle'] );
	}
	if ($link == "l") {
		$projectname = "<a href='$cms_url/projects/?id=" . $pid . "'>" . $projectname . "</a>";
	}
	return $projectname;
}

// Return user note title/link
function notebuild($link, $nid) {
	$noteinfo = mysql_query ( "SELECT * FROM cms_notes WHERE noteid=" . $nid . " LIMIT 0, 1" );
	while ( $noterow = mysql_fetch_assoc ( $noteinfo ) ) {
		$notename = stripslashes ( $noterow ['notetitle'] );
	}
	if ($link == "l") {
		$notename = "<a href='$cms_url/notes/?id=" . $nid . "'>" . $notename . "</a>";
	}
	return $notename;
}

// Return placename text/link
function placenamebuild($link, $placeid, $long) {
	$thisplace = chkformatcache ( 'place', $placeid, $long );
	if ($thisplace) {
		if ($link == 'l') {
			$thisplace = "<a href='$cms_url/archive/places/?id=" . $placeid . "'>" . $thisplace . "</a>";
		}
		return $thisplace;
	} else {

		$placenameinfo = mysql_query ( "SELECT * FROM places WHERE placeid=" . $placeid . " LIMIT 0, 1" );
		if (mysql_num_rows ( $placenameinfo )) {
			$rowplacename = mysql_fetch_assoc ( $placenameinfo );
			$grabparent = mysql_query ( "select node2id,place,abbreviation from events inner join places on places.placeid=events.node2id where node1id=" . $placeid . " and eventedgeid=371 limit 0,1" );
			if (mysql_num_rows ( $grabparent )) {
				$parentplace = mysql_fetch_assoc ( $grabparent );
				if ($long == 'l') {
					$thisplacename = $rowplacename ['place'] . ", " . placenamebuild ( 'n', $parentplace ['node2id'], 'l' );
				} else {
					if ($rowplacename ['wellknown'] == 1) {
						$thisplacename = $rowplacename ['place'];
					} else {
						if ($parentplace ['abbreviation']) {
							$thisplacename = $rowplacename ['place'] . ", " . $parentplace ['abbreviation'];
						} else {
							$thisplacename = $rowplacename ['place'] . ", " . $parentplace ['place'];
						}
					}
				}
			} else {
				$thisplacename = $rowplacename ['place'];
			}

			$thisplace = htmlentities ( $thisplacename, ENT_QUOTES, "UTF-8" );
			mysql_query ( "delete from formatedcache where datatype='place' and dataid=" . $placeid . " and formatstyle='" . $long . "'" );
			mysql_query ( "insert into formatedcache (datatype,dataid,formatstyle,formatedtext) values ('place'," . $placeid . ",'" . $long . "','" . mysql_real_escape_string ( $thisplace ) . "')" );
			if ($link == 'l') {
				$thisplacename = "<a href='$cms_url/archive/places/?id=" . $placeid . "'>" . $thisplacename . "</a>";
			}
			return $thisplacename;
		}
	}
}
function calendarbuild($link, $calendarid) {
	$calendarinfo = mysql_query ( "SELECT * FROM cms_calendar WHERE calendarid=" . $calendarid . " LIMIT 0, 1" );
	if (mysql_num_rows ( $calendarinfo )) {
		while ( $rowcalendar = mysql_fetch_assoc ( $calendarinfo ) ) {
			$thiscalendar = $rowcalendar ['calendartitle'];
			$thiscalendardate = $rowcalendar ['calendarstart'];
			if ($link == 'l') {
				$thiscalendar = "<a href='$cms_url/calendar/?id=" . $calendarid . "'>" . $thiscalendar . ": " . $thiscalendardate . "</a>";
			}
			return $thiscalendar;
		}
	}
}

// Return calendar text/link
function makecalendar($calendarid) {
	$getcalendar = mysql_query ( "SELECT *, Date_Format(calendarstart, '%M %e, %Y') as calendardate, Day(calendarstart) as startday, Day(calendarend) as endday, Time_format(calendarstart, '%l:%i %p') as starttime, Time_format(calendarend, '%l:%i %p') as endtime, date_format(calendarstart, '%M') as startmonth, date_format(calendarend, '%M') as endmonth,Year(calendarstart) as startyear,Year(calendarend) as endyear FROM cms_calendar where calendarid=" . $calendarid . " limit 0,1" );
	if (mysql_num_rows ( $getcalendar )) {
		while ( $geteventrow = mysql_fetch_assoc ( $getcalendar ) ) {
			$calendareventinfo = "<p class='link'><span class='header clear-block'>&#187;" . $geteventrow ['calendartype'] . ": " . $geteventrow ['calendartitle'] . "</span>";
			$calendareventinfo = $calendareventinfo . $geteventrow ['calendardate'];
			if ($geteventrow ['starttime'] != "12:00 AM") {
				$calendareventinfo = $calendareventinfo . ", at " . $geteventrow ['starttime'];
			}
			$calendareventinfo = $calendareventinfo . ", " . $geteventrow ['calendarlocation'];

			$calendareventinfo = $calendareventinfo . "</p>";
		}
		return $calendareventinfo;
	}
}

// Return short calendar text/link
function shortcalendar($calendarid) {
	$getevent = mysql_query ( "SELECT *, Date_Format(calendarstart, '%M %e, %Y') as calendardate, Day(calendarstart) as startday, Day(calendarend) as endday, Time_format(calendarstart, '%l:%i %p') as starttime, Time_format(calendarend, '%l:%i %p') as endtime, date_format(calendarstart, '%M') as startmonth, date_format(calendarend, '%M') as endmonth,Year(calendarstart) as startyear,Year(calendarend) as endyear FROM cms_calendarevents where calendarid=" . $calendarid . " limit 0,1" );
	if (mysql_num_rows ( $getcalendar )) {
		while ( $getcalendarrow = mysql_fetch_assoc ( $getcalendar ) ) {
			$calendareventinfo = $geteventrow ['calendartype'] . ": " . substr ( $geteventrow ['calendartitle'], 0, 20 ) . "... ";
			$calendareventinfo = $calendareventinfo . $geteventrow ['calendardate'];
			$calendareventinfo = $calendareventinfo . ", " . $geteventrow ['calendarlocation'];
		}
		return $calendareventinfo;
	}
}

// Return all event information as array
function buildcalendarinfo($calendarid) {
	$rscalendar = mysql_query ( "select *, Date_Format(calendarstart, '%M %e, %Y') as calendardate, Day(calendarstart) as startday, Day(calendarend) as endday, Time_format(calendarstart, '%l:%i %p') as starttime, Time_format(calendarend, '%l:%i %p') as endtime, date_format(calendarstart, '%M') as startmonth, date_format(calendarend, '%M') as endmonth,Year(calendarstart) as startyear,Year(calendarend) as endyear from cms_calendar where calendarid=" . $calendarid . " limit 0,1" );
	if (mysql_num_rows ( $rscalendar )) {
		$calendarrow = mysql_fetch_assoc ( $rscalendar );
	}
	$calendarrow ['formattedstart'] = $calendarrow ['startmonth'] . " " . $calendarrow ['startday'] . ", " . $calendarrow ['startyear'];
	if ($calendarrow ['calendarend']) {
		$calendarrow ['formattedend'] = $calendarrow ['endmonth'] . " " . $calendarrow ['endday'] . ", " . $calendarrow ['endyear'];
	}
	return $calendarrow;
}

// Get Identifiertype
function getidentifiertype($idtype) {
	$result = mysql_query ( "SELECT identifiertype FROM identifiertypes WHERE identifiertypeid=" . $idtype . " limit 0,1" );
	if (mysql_num_rows ( $result )) {
		$row = mysql_fetch_assoc ( $result );
		return $row ['identifiertype'];
	} else {
		return false;
	}
}

// Get Identifiertype
function getidentifiertypeid($idtype) {
	$result = mysql_query ( "SELECT identifiertypeid FROM identifiertypes WHERE identifiertype='" . $idtype . "' limit 0,1" );
	if (mysql_num_rows ( $result )) {
		$row = mysql_fetch_assoc ( $result );
		return $row ['identifiertypeid'];
	} else {
		return false;
	}
}

// Get Calendartype
function getcalendartype($idtype) {
	$result = mysql_query ( "SELECT calendartype FROM cms_calendartypes WHERE calendartypeid=" . $idtype . " limit 0,1" );
	if (mysql_num_rows ( $result )) {
		$row = mysql_fetch_assoc ( $result );
		return $row ['calendartype'];
	} else {
		return false;
	}
}

// Return things collection text/link
function collectionbuild($link, $title, $em) {
	$title = stripslashes ( $title );
	if (! empty ( $title )) {
		$findcollection = mysql_query ( "Select thingid,title from things where title='" . mysql_real_escape_string ( $title ) . "' limit 0,1" );
		if (mysql_num_rows ( $findcollection )) {
			while ( $collectionrow = mysql_fetch_assoc ( $findcollection ) ) {
				$thingid = $collectionrow ['thingid'];
				if ($link == "l" && $em == "em") {
					return "<em><a href='$cms_url/archive/things/?id=" . $thingid . "' style='text-decoration:none;'>" . $title . "</a></em>";
				} elseif ($link == "l" && $em != "em") {
					return "<a href='$cms_url/archive/things/?id=" . $thingid . "' style='text-decoration:none;'>" . $title . "</a>";
				} elseif ($link != "l" && $em == "em") {
					return "<em>" . $title . "</em>";
				} else {
					return $title;
				}
			}
		} else {
			if ($em == "em") {
				return "<em>" . $title . "</em>";
			} else {
				return $title;
			}
		}
	}
}

// Return personname text/link
function personnamebuild($link, $personnameid, $org) {
	$thisperson = chkformatcache ( 'person', $personnameid, 'firstname', $org );
	if ($thisperson) {
		if ($link == "l") {
			$thisperson = "<a href='$cms_url/archive/people/?id=" . $personnameid . "'>" . $thisperson . "</a>";
		}
		return $thisperson;
	} else {
		$personnameinfo = mysql_query ( "SELECT lastname,middlename,firstname,title,suffix,personid FROM people WHERE personid=" . $personnameid . " LIMIT 0, 1" );
		while ( $rowpersonname = mysql_fetch_assoc ( $personnameinfo ) ) {
			if (! empty ( $rowpersonname ['title'] )) {
				$personname = $rowpersonname ['title'] . " " . stripslashes ( $rowpersonname ['firstname'] ) . " ";
			} else {
				$personname = stripslashes ( $rowpersonname ['firstname'] ) . " ";
			}
			if (! empty ( $rowpersonname ['middlename'] )) {
				$personname = $personname . stripslashes ( $rowpersonname ['middlename'] ) . " ";
			}

			$rsuser = mysql_query ( "SELECT * FROM cms_users where personid=" . $personnameid . " limit 0,1" );
			if (! empty ( $rsuser )) {
				while ( $rsuserrow = mysql_fetch_assoc ( $rsuser ) ) {
					$rsorg = mysql_query ( "SELECT * FROM organizations where organizationid=" . $rsuserrow ['organization'] . " limit 0,1" );
					if (empty ( $rsorg )) {
						$thisorganizationname = "";
					} else {
						while ( $roworg = mysql_fetch_assoc ( $rsorg ) ) {
							$emailobscure = urlencode ( $rsuserrow ['email'] );
							switch ($org) {
								case "a" :
									$thisorganizationname = " (" . $roworg ['organizationabbr'] . ")";
									break;
								case "l" :
									$thisorganizationname = " (" . $rsuserrow ['department'] . ", " . $roworg ['organizationname'] . ") ";
									break;
								case "si" :
									$thisorganizationname = " (" . $roworg ['organizationname'] . ", " . $rsuserrow ['email'] . ")";
									break;
								case "n" :
									$thisorganizationname = "";
									break;
							}
						}
					}
				}
			}

			$personname = $personname . stripslashes ( $rowpersonname ['lastname'] );
			if (! empty ( $rowpersonname ['suffix'] )) {
				$personname = $personname . ", " . $rowpersonname ['suffix'];
			}
			$thisid = $personname . $rowpersonname ['personid'];

			if ($link == "l") {
				$personname = "<a id='" . str_replace ( " ", "", $thisid ) . "' href='$cms_url/archive/people/?id=" . $rowpersonname ['personid'] . "'>" . $personname . "</a>" . $thisorganizationname;
			} else {
				$personname = $personname . $thisorganizationname;
			}
			$thisperson = htmlentities ( strip_tags ( $personname ), ENT_QUOTES, "UTF-8" );
			mysql_query ( "delete from formatedcache where datatype='person' and dataid=" . $personnameid . " and formatstyle='firstname' and formatform='" . $org . "'" );
			mysql_query ( "insert into formatedcache (datatype,dataid,formatstyle,formatedtext,formatform) values ('person'," . $personnameid . ",'firstname','" . mysql_real_escape_string ( $thisperson ) . "','" . $org . "')" );
			return $personname;
		}
	}
}


// Return all place information as array
function buildplaceinfo($placeid) {
	$rsplace = mysql_query ( "select * from places where placeid=" . $placeid . " limit 0,1" );
	if (mysql_num_rows ( $rsplace )) {
		$placerow = mysql_fetch_assoc ( $rsplace );
		$getexists = mysql_query ( "select eventid from events where node1id=" . $placerow ['placeid'] . " and node2id=5 and eventedgeid=257 limit 0,1" );
		if (mysql_num_rows ( $getexists )) {
			$thisexists = mysql_fetch_assoc ( $getexists );

			$grabstartdate = mysql_query ( "select * from datesvalid where dateforid=" . $thisexists ['eventid'] . " and datefortype='event' and validtype='start' limit 0,1" );
			if (mysql_num_rows ( $grabstartdate )) {
				$placestartrow = mysql_fetch_assoc ( $grabstartdate );
				$placerow ['start'] = $placestartrow ['datetext'];
				$placerow ['startamb'] = $placestartrow ['dateamb'];
				$placerow ['startjulian'] = $placestartrow ['julian'];
				$placerow ['startcal'] = $placestartrow ['datecal'];
			}

			$grabenddate = mysql_query ( "select * from datesvalid where dateforid=" . $thisexists ['eventid'] . " and datefortype='event' and validtype='end' limit 0,1" );
			if (mysql_num_rows ( $grabenddate )) {
				$placeendrow = mysql_fetch_assoc ( $grabenddate );
				$placerow ['end'] = $placeendrow ['datetext'];
				$placerow ['endamb'] = $placeendrow ['dateamb'];
				$placerow ['endjulian'] = $placeendrow ['julian'];
				$placerow ['endcal'] = $placeendrow ['datecal'];
			}
		}
	}
	return $placerow;
}

// Return all organization information as array
function buildorginfo($orgid) {
	$orgrow = array ();
	$rsorg = mysql_query ( "select organizations.organizationid,organizationname,
othernameid,datesid,url,placeid,organizations.organizationtypeid,organizationtype from organizations inner join organizationtypes on organizationtypes.organizationtypeid=organizations.organizationtypeid
where organizationid=" . $orgid . " limit 0,1" );
	if (mysql_num_rows ( $rsorg )) {
		$orgrow = mysql_fetch_assoc ( $rsorg );
		$getexists = mysql_query ( "select eventid from events where node1id=" . $orgrow ['organizationid'] . " and node2id=5 and eventedgeid=257 limit 0,1" );
		if (mysql_num_rows ( $getexists )) {
			$thisexists = mysql_fetch_assoc ( $getexists );

			$grabstartdate = mysql_query ( "select * from datesvalid where dateforid=" . $thisexists ['eventid'] . " and datefortype='event' and validtype='start' limit 0,1" );
			if (mysql_num_rows ( $grabstartdate )) {
				$orgstartrow = mysql_fetch_assoc ( $grabstartdate );
				$orgrow ['start'] = $orgstartrow ['datetext'];
				$orgrow ['startamb'] = $orgstartrow ['dateamb'];
				$orgrow ['startjulian'] = $orgstartrow ['julian'];
				$orgrow ['startcal'] = $orgstartrow ['datecal'];
			}

			$grabenddate = mysql_query ( "select * from datesvalid where dateforid=" . $thisexists ['eventid'] . " and datefortype='event' and validtype='end' limit 0,1" );
			if (mysql_num_rows ( $grabenddate )) {
				$orgendrow = mysql_fetch_assoc ( $grabenddate );
				$orgrow ['end'] = $orgendrow ['datetext'];
				$orgrow ['endamb'] = $orgendrow ['dateamb'];
				$orgrow ['endjulian'] = $orgendrow ['julian'];
				$orgrow ['endcal'] = $orgendrow ['datecal'];
			}
		}
	}
	return $orgrow;
}

// Return all user note information as array
function buildnoteinfo($noteid) {
	$rsnote = mysql_query ( "select * from cms_notes where noteid=" . $noteid . " limit 0,1" );
	if (mysql_num_rows ( $rsnote )) {
		$noterow = mysql_fetch_assoc ( $rsnote );
		$noterow ['person'] = getuserperson ( $noterow ['noteowner'] );
	}
	return $noterow;
}

// Return personid for a user
function getuserperson($userid) {
	$rsperson = mysql_query ( "select personid from cms_users where userid='" . $userid . "' limit 0,1" );
	if (mysql_num_rows ( $rsperson )) {
		$personrow = mysql_fetch_assoc ( $rsperson );
	}
	return $personrow ['personid'];
}

// Return all user project information as array
function buildprojectinfo($projectid) {
	$rsproject = mysql_query ( "select * from cms_projects where projectid=" . $projectid . " limit 0,1" );
	if (mysql_num_rows ( $rsproject )) {
		$projectrow = mysql_fetch_assoc ( $rsproject );
	}
	return $projectrow;
}

// Return all discussion information as array
function builddiscussioninfo($discussionid) {
	$rsdiscussion = mysql_query ( "select * from cms_discussions where discussionid=" . $discussionid . " limit 0,1" );
	if (mysql_num_rows ( $rsdiscussion )) {
		$discussionrow = mysql_fetch_assoc ( $rsdiscussion );
	}
	return $discussionrow;
}

// Return all person information as array
function buildpersoninfo($personid) {
	$rsperson = mysql_query ( "select * from people where personid=" . $personid . " limit 0,1" );
	if (mysql_num_rows ( $rsperson )) {
		$personrow = mysql_fetch_assoc ( $rsperson );

		$getbirth = mysql_query ( "select eventid from events where node1id=" . $personid . " and node2id=2 and eventedgeid=257 limit 0,1" );
		if (mysql_num_rows ( $getbirth )) {
			$thisbirth = mysql_fetch_assoc ( $getbirth );

			$grabbirthdate = mysql_query ( "select * from datesvalid where dateforid=" . $thisbirth ['eventid'] . " and datefortype='event' and validtype='start' limit 0,1" );
			if (mysql_num_rows ( $grabbirthdate )) {
				$personbirthrow = mysql_fetch_assoc ( $grabbirthdate );
				$personrow ['dob'] = $personbirthrow ['datetext'];
				$personrow ['dobamb'] = $personbirthrow ['dateamb'];
				$personrow ['dobjulian'] = $personbirthrow ['julian'];
				$personrow ['dobcal'] = $personbirthrow ['datecal'];
			}
			$getbirthplace = mysql_query ( "select node2id from events where node1id=" . $thisbirth ['eventid'] . " and eventedgeid=500 limit 0,1" );
			if (mysql_num_rows ( $getbirthplace )) {
				$personrow ['pob'] = implode ( '', mysql_fetch_assoc ( $getbirthplace ) );
			}
			if ($personrow ['pob']) {
				$findplaceb = mysql_query ( "SELECT * FROM places WHERE placeid=" . $personrow ['pob'] );
				if (mysql_num_rows ( $findplaceb )) {
					while ( $findplacebrow = mysql_fetch_assoc ( $findplaceb ) ) {
						$personrow ['pobname'] = $findplacebrow ['place'];
					}
				}
			}
		}

		$getdeath = mysql_query ( "select eventid from events where node1id=" . $personid . " and node2id=3 and eventedgeid=257 limit 0,1" );
		if (mysql_num_rows ( $getdeath )) {
			$thisdeath = mysql_fetch_assoc ( $getdeath );

			$grabdeathdate = mysql_query ( "select * from datesvalid where dateforid=" . $thisdeath ['eventid'] . " and datefortype='event' and validtype='start' limit 0,1" );
			if (mysql_num_rows ( $grabdeathdate )) {
				$persondeathrow = mysql_fetch_assoc ( $grabdeathdate );
				$personrow ['dod'] = $persondeathrow ['datetext'];
				$personrow ['dodamb'] = $persondeathrow ['dateamb'];
				$personrow ['dodjulian'] = $persondeathrow ['julian'];
				$personrow ['dodcal'] = $persondeathrow ['datecal'];
			}
			$getdeathplace = mysql_query ( "select node2id from events where node1id=" . $thisdeath ['eventid'] . " and eventedgeid=500 limit 0,1" );
			if (mysql_num_rows ( $getdeathplace )) {
				$personrow ['pod'] = implode ( '', mysql_fetch_assoc ( $getdeathplace ) );
			}

			if ($personrow ['pod']) {
				$findplaced = mysql_query ( "SELECT * FROM places WHERE placeid=" . $personrow ['pod'] );
				if (mysql_num_rows ( $findplaced )) {
					while ( $findplacedrow = mysql_fetch_assoc ( $findplaced ) ) {
						$personrow ['podname'] = $findplacedrow ['place'];
					}
				}
			}
		}

		$thisperson = $personrow;
	} else {
		$thisperson = "Not a person";
	}

	return $thisperson;
}


// Check if organization from bibliographic data exists in DB
function chkorg($org) {
	// need to resolve for multiple places - for now, dumb everything after the first 'and' and comma
	$chksemi = stripos ( $org, ";" );
	if ($chksemi) {
		$org = substr ( $org, 0, $chksemi );
	}
	// clean [ua] out
	$chkbracket = stripos ( $org, "[" );
	if ($chkbracket) {
		$org = substr ( $org, 0, $chkbracket );
	}

	$presskeywords = array (
			'ress',
			'erlag',
			'mprim'
	);
	$edukeywords = array (
			'niver',
			'choo',
			'col',
			'nstit'
	);
	$gallerykeywords = array (
			'epositor',
			'useum',
			'allery',
			'rchiv',
			'glise',
			'hurch',
			'ssembl',
			'overnment',
			'ouvernme',
			'rmy',
			'avy',
			'ivil',
			'ffice',
			'ervice',
			'ommittee',
			'ouncil',
			'onference',
			'ibrary'
	);

	$cleanedtheseorganizations = trim ( rtrim ( urldecode ( $org ), '.' ) );
	$findtheseorganizations = str_replace ( " ", "%", $cleanedtheseorganizations );
	$checkstrpos = strpos_array ( $cleanedtheseorganizations, $presskeywords );
	if (strpos_array ( $cleanedtheseorganizations, $presskeywords ) > 0) {
		// press
		$findorganizationssql = "select organizationid,organizationname from organizations where organizationname like '%" . mysql_real_escape_string ( $findtheseorganizations ) . "%' and organizationtypeid=4 order by organizationname desc";
	} elseif (strpos_array ( $cleanedtheseorganizations, $gallerykeywords ) > 0) {
		// sitory
		$findorganizationssql = "select organizationid,organizationname from organizations where organizationname like '%" . mysql_real_escape_string ( $findtheseorganizations ) . "%' and organizationtypeid=5 order by organizationname asc";
	} elseif (strpos_array ( $cleanedtheseorganizations, $edukeywords ) > 0) {
		// university school
		$findorganizationssql = "select organizationid,organizationname from organizations where organizationname like '%" . mysql_real_escape_string ( $findtheseorganizations ) . "%' and organizationtypeid in (6,7) order by organizationname asc";
	} else {
		$findorganizationssql = "select organizationid,organizationname from organizations where organizationname like '%" . mysql_real_escape_string ( $findtheseorganizations ) . "%' order by organizationname asc";
	}

	$c = 0;
	$findorganizations = mysql_query ( $findorganizationssql );
	if (mysql_num_rows ( $findorganizations )) {
		while ( $theseorganizationrows = mysql_fetch_assoc ( $findorganizations ) ) {
			$possibles [$c] ['id'] = $theseorganizationrows ['organizationid'];
			$possibles [$c] ['name'] = stripslashes ( $theseorganizationrows ['organizationname'] );
			$c ++;
		}
	} else {
		$possibles = FALSE;
	}
	return $possibles;
}


function getallidentifiers($id, $type) {
	$grabidtypes = mysql_query ( "select distinct identifiertypeid from identifiers where identifiers.linkid=" . $id . " and
identifiers.linkitem='" . $type . "'" );
	if (mysql_num_rows ( $grabidtypes )) {
		while ( $grabidtyperow = mysql_fetch_assoc ( $grabidtypes ) ) {

			$grabids = mysql_query ( "select identifiers.identifier,identifiertypes.identifiertype from
identifiers inner join identifiertypes on
identifiers.identifiertypeid=identifiertypes.identifiertypeid where identifiers.linkid=" . $id . " and
identifiers.linkitem='" . $type . "' and identifiers.identifiertypeid=" . $grabidtyperow ['identifiertypeid'] );
			if (mysql_num_rows ( $grabids )) {
				while ( $grabidrow = mysql_fetch_assoc ( $grabids ) ) {
					$allids [$grabidrow ['identifiertype']] [] = $grabidrow ['identifier'];
				}
			}
		}
		return $allids;
	} else {
		return null;
	}
}


/* ******************************UTILITY FUNCTIONS************************************* */

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

//Create list with commas and last item 'and'
function listing($items, $and = ' and ') {
	if (is_array($items)) {
	if(count($items)>1){
		$lastItem = array_pop($items);
		$items = implode(', ', $items);
		$items = $items . $and . $lastItem;
	}else{
	$items = implode('', $items);
	}
	}

	return $items;
}

function getjpegsize($img_loc) {
    $handle = fopen($img_loc, "rb") or die("Invalid file stream.");
    $new_block = NULL;
    if(!feof($handle)) {
        $new_block = fread($handle, 32);
        $i = 0;
        if($new_block[$i]=="\xFF" && $new_block[$i+1]=="\xD8" && $new_block[$i+2]=="\xFF" && $new_block[$i+3]=="\xE0") {
            $i += 4;
            if($new_block[$i+2]=="\x4A" && $new_block[$i+3]=="\x46" && $new_block[$i+4]=="\x49" && $new_block[$i+5]=="\x46" && $new_block[$i+6]=="\x00") {
                // Read block size and skip ahead to begin cycling through blocks in search of SOF marker
                $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                $block_size = hexdec($block_size[1]);
                while(!feof($handle)) {
                    $i += $block_size;
                    $new_block .= fread($handle, $block_size);
                    if($new_block[$i]=="\xFF") {
                        // New block detected, check for SOF marker
                        $sof_marker = array("\xC0", "\xC1", "\xC2", "\xC3", "\xC5", "\xC6", "\xC7", "\xC8", "\xC9", "\xCA", "\xCB", "\xCD", "\xCE", "\xCF");
                        if(in_array($new_block[$i+1], $sof_marker)) {
                            // SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.
                            $size_data = $new_block[$i+2] . $new_block[$i+3] . $new_block[$i+4] . $new_block[$i+5] . $new_block[$i+6] . $new_block[$i+7] . $new_block[$i+8];
                            $unpacked = unpack("H*", $size_data);
                            $unpacked = $unpacked[1];
                            $height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
                            $width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
                            return array($width, $height);
                        } else {
                            // Skip block marker and read block size
                            $i += 2;
                            $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                            $block_size = hexdec($block_size[1]);
                        }
                    } else {
                        return FALSE;
                    }
                }
            }
        }
    }
    return FALSE;
}

function is_odd($num) {
  if ($num % 2 == 0) {
  return false;
 } else {
    return true;
  }
}

//Check if html string ends in open / incomplete tag
function closetags($html) {
#put all opened tags into an array
preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
$openedtags = $result[1];   #put all closed tags into an array
preg_match_all('#</([a-z]+)>#iU', $html, $result);
$closedtags = $result[1];
$len_opened = count($openedtags);
# all tags are closed
if (count($closedtags) == $len_opened) {
return $html;
}
$openedtags = array_reverse($openedtags);
# close tags
for ($i=0; $i < $len_opened; $i++) {
if (!in_array($openedtags[$i], $closedtags)){
$html .= '</'.$openedtags[$i].'>';
} else {
unset($closedtags[array_search($openedtags[$i], $closedtags)]);
}
}
return $html;
}

function cleancommas($text){
$text=preg_replace('/,+/', ',',$text);
$text=trim($text,",");
return $text;
}

function countpdfpages($file){
         //where $file is the full path to your PDF document.
         if(file_exists($file)) {
                         //open the file for reading
             if($handle = @fopen($file, "rb")) {
                 $count = 0;
                 $i=0;
                 while (!feof($handle)) {
                     if($i > 0) {
                         $contents .= fread($handle,8152);
                     }
                     else {
                           $contents = fread($handle, 1000);
                         //In some pdf files, there is an N tag containing the number of
                         //of pages. This doesn't seem to be a result of the PDF version.
                         //Saves reading the whole file.
                         if(preg_match("/\/N\s+([0-9]+)/", $contents, $found)) {
                             return $found[1];
                         }
                     }
                     $i++;
                 }
                 fclose($handle);

                 //get all the trees with 'pages' and 'count'. the biggest number
                 //is the total number of pages, if we couldn't find the /N switch above.
                 if(preg_match_all("/\/Type\s*\/Pages\s*.*\s*\/Count\s+([0-9]+)/", $contents, $capture, PREG_SET_ORDER)) {
                     foreach($capture as $c) {
                         if($c[1] > $count)
                             $count = $c[1];
                     }
                     return $count;
                 }
             }
         }
         return 0;
		 }

function greaterDate($start_date,$end_date){
  $start = strtotime($start_date);
  $end = strtotime($end_date);
  if ($start-$end > 0)
    return 1;
  else
   return 0;
}

function array_remove_keys($array, $keys = array()) {

    // If array is empty or not an array at all, don't bother
    // doing anything else.
    if(empty($array) || (! is_array($array))) {
        return $array;
    }

    // If $keys is a comma-separated list, convert to an array.
    if(is_string($keys)) {
        $keys = explode(',', $keys);
    }

    // At this point if $keys is not an array, we can't do anything with it.
    if(! is_array($keys)) {
        return $array;
    }

    // array_diff_key() expected an associative array.
    $assocKeys = array();
    foreach($keys as $key) {
        $assocKeys[$key] = true;
    }

    return array_diff_key($array, $assocKeys);
}

function strstrb($h,$n){
    return array_shift(explode($n,$h,2));
}

function exists($u){
$handle = @fopen($u,'r');
return $handle;
}

function r_implode( $glue, $pieces ){
	foreach( $pieces as $r_pieces )
	{
    		if( is_array( $r_pieces ) ){
      			$retVal[] = r_implode( $glue, $r_pieces );
    		}else{
      			$retVal[] = $r_pieces;
    		}
  	}
  	return implode( $glue, $retVal );
}

function strpos_array($haystack, $needles) {
    if ( is_array($needles) ) {
        foreach ($needles as $str) {
            if ( is_array($str) ) {
                $pos = strpos_array($haystack, $str);
            } else {
                $pos = stripos($haystack, $str);
            }
            if ($pos !== FALSE) {
                return $pos;
            }
        }
    } else {
        return strpos($haystack, $needles);
    }
}

/**
 * create_time_range
 *
 * @param mixed $start start time, e.g., 9:30am or 9:30
 * @param mixed $end   end time, e.g., 5:30pm or 17:30
 * @param string $by   1 hour, 1 mins, 1 secs, etc.
 * @access public
 * @return void
 */
function create_time_range($start, $end, $by='5 mins') {

    $start_time = strtotime($start);
    $end_time   = strtotime($end);

    $current    = time();
    $add_time   = strtotime('+'.$by, $current);
    $diff       = $add_time-$current;

    $times = array();
    while ($start_time < $end_time) {
        $times[] = $start_time;
        $start_time += $diff;
    }
    $times[] = $start_time;
    return $times;
}

function _make_url_clickable_cb($matches) {
	$ret = '';
	$url = $matches[2];
	if ( empty($url) )
		return $matches[0];
	// removed trailing [.,;:] from URL
	if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($url, -1);
		$url = substr($url, 0, strlen($url)-1);
	}
	return $matches[1] . "<a href=\"$url\" target=\"_blank\">$url</a>" . $ret;
}

function _make_web_ftp_clickable_cb($matches) {
	$ret = '';
	$dest = $matches[2];
	$dest = 'http://' . $dest;
	if ( empty($dest) )
		return $matches[0];
	// removed trailing [,;:] from URL
	if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($dest, -1);
		$dest = substr($dest, 0, strlen($dest)-1);
	}
	return $matches [1] . "<a href=\"$dest\" target=\"_blank\">$dest</a>" . $ret;
}

function _make_email_clickable_cb($matches) {
	$email = $matches [2] . '@' . $matches [3];
	return $matches [1] . "<a href=\"mailto:$email\">$email</a>";
}

function make_clickable($ret) {
	$ret = ' ' . $ret;
	// in testing, using arrays here was found to be faster
	$ret = preg_replace_callback ( '#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret );
	$ret = preg_replace_callback ( '#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret );
	$ret = preg_replace_callback ( '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret );
	// this one is not in an array because we need it to run last, for cleanup of accidental links within links
	$ret = preg_replace ( "#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret );
	$ret = trim ( $ret );
	return $ret;
}

function do_post_request($url, $data, $optional_headers = null) {
	$params = array (
			'http' => array (
					'method' => 'POST',
					'content' => $data
			)
	);
	if ($optional_headers !== null) {
		$params ['http'] ['header'] = $optional_headers;
	}
	$ctx = stream_context_create ( $params );
	$fp = @fopen ( $url, 'rb', false, $ctx );
	if (! $fp) {
		throw new Exception ( "Problem with $url, $php_errormsg" );
	}
	$response = @stream_get_contents ( $fp );
	if ($response === false) {
		throw new Exception ( "Problem reading data from $url, $php_errormsg" );
	}
	return $response;
}

function convert_smart_quotes($string) {
	$search = array (
			chr ( 145 ),
			chr ( 146 ),
			chr ( 147 ),
			chr ( 148 ),
			chr ( 151 )
	);

	$replace = array (
			"'",
			"'",
			'"',
			'"',
			'-'
	);

	return str_replace ( $search, $replace, $string );
}

function array_iunique($array) {
	return array_intersect_key ( $array, array_unique ( array_map ( strtolower, $array ) ) );
}

// get days in month by calendar
function getmonthdays($c, $m, $y) {
	switch ($c) {
		case "gregorian" :
			$thismonthdays = cal_days_in_month ( CAL_GREGORIAN, $m, $y );
			break;
		case "julian" :
			$thismonthdays = cal_days_in_month ( CAL_JULIAN, $m, $y );
			break;
		case "french" :
			$thismonthdays = cal_days_in_month ( CAL_FRENCH, $m, $y );
			break;
		case "jewish" :
			$thismonthdays = cal_days_in_month ( CAL_JEWISH, $m, $y );
			break;
	}
	return $thismonthdays;
}

// get julianday by calendar
function makejulianbycalendar($m, $d, $y, $c) {
	switch ($c) {
		case "gregorian" :
			$thisjulian = gregoriantojd ( $m, $d, $y );
			break;
		case "julian" :
			$thisjulian = juliantojd ( $m, $d, $y );
			break;
		case "french" :
			$thisjulian = frenchtojd ( $m, $d, $y );
			break;
		case "jewish" :
			$thisjulian = jewishtojd ( $m, $d, $y );
			break;
	}
	return $thisjulian;
}

// Get contents of page URL
function getContents($url) {
	$ch = curl_init ( $url );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 ); // DO NOT RETURN HTTP HEADERS
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // RETURN THE CONTENTS
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 0 );
	$Rec_Data = curl_exec ( $ch );
	return $Rec_Data;
}

// Cleans all MS office curly characters from strings
function cleancurly($text) {
	// First, replace UTF-8 characters.
	$text = str_replace ( array (
			"\xe2\x80\x98",
			"\xe2\x80\x99",
			"\xe2\x80\x9c",
			"\xe2\x80\x9d",
			"\xe2\x80\x93",
			"\xe2\x80\x94",
			"\xe2\x80\xa6"
	), array (
			"'",
			"'",
			'"',
			'"',
			'-',
			'--',
			'...'
	), $text );
	// Next, replace their Windows-1252 equivalents.
	$text = str_replace ( array (
			chr ( 145 ),
			chr ( 146 ),
			chr ( 147 ),
			chr ( 148 ),
			chr ( 150 ),
			chr ( 151 ),
			chr ( 133 )
	), array (
			"'",
			"'",
			'"',
			'"',
			'-',
			'--',
			'...'
	), $text );
	return $text;
}

function timeago($time) {
	$periods = array (
			"second",
			"minute",
			"hour",
			"day",
			"week",
			"month",
			"year",
			"decade"
	);
	$lengths = array (
			"60",
			"60",
			"24",
			"7",
			"4.35",
			"12",
			"10"
	);

	$now = time ();

	$difference = $now - $time;
	$tense = "ago";

	for($j = 0; $difference >= $lengths [$j] && $j < count ( $lengths ) - 1; $j ++) {
		$difference /= $lengths [$j];
	}

	$difference = round ( $difference );

	if ($difference != 1) {
		$periods [$j] .= "s";
	}

	return "$difference $periods[$j] ago";
}

function is_valid_callback($subject) {
	$identifier_syntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

	$reserved_words = array (
			'break',
			'do',
			'instanceof',
			'typeof',
			'case',
			'else',
			'new',
			'var',
			'catch',
			'finally',
			'return',
			'void',
			'continue',
			'for',
			'switch',
			'while',
			'debugger',
			'function',
			'this',
			'with',
			'default',
			'if',
			'throw',
			'delete',
			'in',
			'try',
			'class',
			'enum',
			'extends',
			'super',
			'const',
			'export',
			'import',
			'implements',
			'let',
			'private',
			'public',
			'yield',
			'interface',
			'package',
			'protected',
			'static',
			'null',
			'true',
			'false'
	);

	return preg_match ( $identifier_syntax, $subject ) && ! in_array ( mb_strtolower ( $subject, 'UTF-8' ), $reserved_words );
}

function chkforquerystring($s){
if(stripos($s,'?')){
return true;
}else{
return false;
}
}

function split2($string,$needle,$nth){
$max = strlen($string);
$n = 0;
for($i=0;$i<$max;$i++){
    if($string[$i]==$needle){
        $n++;
        if($n>=$nth){
            break;
        }
    }
}
$arr[] = substr($string,0,$i);
$arr[] = substr($string,$i+1,$max);

return $arr;
}?>
<?php /*page handling. will handle sanitization of php based on user profiling*/
define("ABSPATH", dirname(__FILE__));
include (ABSPATH."/../../includes/phpheader.php");
header ( "Expires: Mon, 01 Jul 2003 00:00:00 GMT" ); // Past date
header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
header ( "Cache-Control: no-cache, must-revalidate" ); // HTTP/1.1
header ( "Pragma: no-cache" ); // NO CACHE
?>
<div id="modalcontent" class="w500">
	<?php
	if($_SESSION['user']){
		if (! empty ( $_POST ['pagetitle'] )) {
			$pagetitle = "'" . mysql_real_escape_string ( $_POST ['pagetitle'] ) . "'";
		} else {
			$pagetitle = "NULL";
		}

		if (! empty ( $_POST ['pageaccess'] )) {
			$pageaccess = $_POST ['pageaccess'];
		} else {
			$pageaccess = "NULL";
		}

		$modifiedby = $_SESSION ['user'];
		$modified = date ( 'Y:m:d H:i:s' );
		$thispageid = $_POST ['pageid'];
		$uniqueid = $_POST ['pageunique'];
		$revision = $_POST ['pagerevision'];
		if (empty($_POST['pageurl'])) $pageurl=NULL;
		else {
			$pageurl = $_POST['pageurl'];
			if (substr($pageurl, 0,1) != "/") $pageurl= "/".$pageurl;
		}

		$pagecontent=sanitize_content( $_POST ['pagecontents'] );
		$pagejs=sanitize_content($_POST ['pagejs']);
		$jsfiles=sanitize_content($_POST['pagejsfiles']);
		$css=sanitize_content($_POST['pagecss']);
		$cssfiles=sanitize_content($_POST['pagecssfiles']);


		switch($_GET['j']){
			case "a":
				if($_POST['new']=='1' && empty($_POST ['pageid'])){
					//add new page to current revision
					mysql_query("Insert into cms_contentsinfo (content_title,content_url) values (" . $pagetitle . ",'".$pageurl."')");
					$thispageid=mysql_insert_id();
					mysql_query("Insert into cms_contents
				(content, content_moddate, content_modby, content_for, content_js,content_id,content_jsfiles,content_css, content_cssfiles) values
				(".$pagecontent.",'". $modified."','".$modifiedby."',".$pageaccess.",".$pagejs.",".$thispageid.",".$jsfiles.",".$css.",".$cssfiles.")");

				}elseif($_POST['new']=='0' && $_POST ['pageid']){
					//add new revision, then new page. Assumes page id exists. Update all old versions first, then do inserts.
					$sqlc = "Insert into cms_contents
							(content, content_moddate, content_modby, content_for, content_js,content_id,content_jsfiles,content_css, content_cssfiles) values
							(".$pagecontent.",'". $modified."','".$modifiedby."',".$pageaccess.",".$pagejs.",".$thispageid.",".$jsfiles.",".$css.",".$cssfiles.")";
					mysql_unbuffered_query ( $sqlc );
				}
				break;
			case "e":
				//only 'edit' if we have a pageid (content_id) and pageunique (unique_id) as well
				if($_POST ['pageid']!==false && $_POST ['pageunique']!==false){
					$sqlih = "insert into cms_contentsinfo_history (content_current_rev,content_id,content_lock,content_tags,content_title,content_url,content_timestamp) select
									content_current_rev,content_id,content_lock,content_tags,content_title,content_url,now() as content_timestamp from cms_contentsinfo where content_id=" . $thispageid;
					$sqlch = "insert into cms_contents_history (content,content_css,content_cssfiles,content_for,content_id,content_js,content_jsfiles,content_made,content_madeby,content_modby,content_moddate
						,content_rev,content_unique,content_timestamp) select content,content_css,content_cssfiles,content_for,content_id,content_js,content_jsfiles,content_made,content_madeby,content_modby,content_moddate
						,content_rev,content_unique,now() as content_timestamp from cms_contents where content_unique=" . $uniqueid;
					$sqli = "UPDATE cms_contentsinfo SET content_title=" . $pagetitle.",content_url='".$pageurl."' where content_id=" . $thispageid;
					$sqlc = " UPDATE cms_contents SET
								content=" . $pagecontent . ", content_moddate='" . $modified . "',content_modby='" . $modifiedby . "',content_for=" . $pageaccess . ", content_js=" . $pagejs.",".
								"content_jsfiles=".$jsfiles.",content_css=".$css.",content_cssfiles=".$cssfiles." where content_unique=" . $uniqueid;
					//not sure why these are unbuffered
					mysql_unbuffered_query ( $sqli );
					mysql_unbuffered_query ( $sqlih );
					mysql_unbuffered_query ( $sqlc );
					mysql_unbuffered_query ( $sqlch );
				}
				break;
			case "d":
				//no pages are deleted - though pages should be deleted from contents_info and contents if no longer needed, but not from history.
				break;
		}


		if (mysql_errno () != 0) {
			$contentsuccess = FALSE;
			//print error statements
			print $sqlc."<br />";
			print mysql_error()."<br />";
		} else {
			$contentsuccess = TRUE;
		}

		if ($contentsuccess) {
			/*
			 * add page tags here - needs testing & fix
			*
			$deletesql = "delete from cms_keywords WHERE linkid=" . $thispageid . " and linktype='page'";
			mysql_query ( $deletesql );
			if ($_POST ['pagetags']) {
			$keywordarray = explode ( ',', mysql_real_escape_string ( cleancurly ( $_POST ['pagetags'] ) ) );
			foreach ( $keywordarray as $newkeyword ) {
			$newkeyword = trim ( $newkeyword );
			if ($newkeyword != NULL) {
				if (! ctype_punct ( $newkeyword ) && strlen ( $newkeyword ) >= 2) {
					if ($newkeyword != "" || $newkeyword != " ") {
						$sql = "INSERT INTO cms_keywords (linkid,keyword,linktype)". " VALUES ". "(" . $thispageid . ",'" . $newkeyword . "','page')";
			mysql_query ( $sql );
			}
			}
			}
			}
			}*/


			if ($_GET ['n'] != "n") {
		?>
	<h3>The updates to this page were successful!</h3>
	<a class="popmodal makebutton"
		href="/cms/page/manage.php?id=<?php print $_POST['pageid'];?>">go back
		to manage this page</a> <a class="makebutton"
		href="<?php print $cms_url;?><?php print $pageurl;?>">close and reload</a>
	<?php	} else {
		?>
	<h3>The updates to this page were successful!</h3>
	<a class="makebutton"
		href="<?php print $cms_url;?><?php print $pageurl;?>"> <?php if ($adding) echo "View this page";
	else echo "Reload"; ?>
	</a>
	<?php

	}
		} else {
	if (! $contentsuccess) {
		?>
	content entry unsuccessful <br />
	<?php
}
}
}
?>
	<script>
$('.tabs').tabs();
		$('.popmodal').magnificPopup({
			  type: 'ajax'
		});

</script>
</div>

<?php /*load user preferences - not in use at the moment. merge with phpheader.php / login when needed?*/
$userlogin = $_SESSION ['user'];
$userpwd = $_SESSION ['password'];
if ($userlogin != "" && (! empty ( $userlogin ))) {
	$rs = mysql_query ( "Select userid,password from cms_users where userid='" . $userlogin . "' limit 0, 1" );
	$currentpage=$_SERVER['SERVER_NAME'];
	if ($currentpage != "localhost") {
		$currentpage = "";
		$_SESSION ['currentpage'] = "";
	} else {
		$_SESSION ['currentpage'] = "http://" . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] . "?" . $_SERVER ['QUERY_STRING'];
	}

	//If this user does not exist...
	if (empty ( $rs )) {
		header ( "Location: $cms_url/login.php" );
	} else { //User exists. Check password
		while ( $row = mysql_fetch_assoc ( $rs ) ) {
			$userstr = $row ['userid'];
			$passwordstr = $row ['password'];
		}
		if ($userpwd == "" or $userpwd != $passwordstr) {
			//Password is empty or doesn't match; redirect to login.
			header ( "Location: $cms_url/login.php" );
		} elseif ($userpwd == $passwordstr) {
			//Password matches; set session data from database.
			//$rs = mysql_query ( "SELECT * FROM cms_users inner join cms_userpreferences on cms_users.userid=cms_userpreferences.userid where cms_users.userid='" . $userstr . "' and cms_users.password='" . $passwordstr . "' and preferenceset='full' limit 0, 1" );
			$rs = mysql_query ("select * from cms_users where userid=".$userstr);
			while ( $row = mysql_fetch_assoc ( $rs ) ) {
				$_SESSION ['user'] = $row ['userid'];
				$_SESSION ['email'] = $row ['email'];
				$_SESSION ['firstname'] = $row['firstname'];
				$_SESSION ['lastname'] = $row['lastname'];
				$_SESSION ['profile'] = $row ['profile'];
				$_SESSION ['userlock'] = $row ['lockuser'];
				$_SESSION ['userhash'] = $row ['hashid'];
				$_SESSION ['userinterview'] = $row ['userinterview'];
				$_SESSION ['agree'] = $row ['agree'];
				//$_SESSION ['zippostalcode'] = $row ['zippostalcode'];
				//$_SESSION ['city'] = $row ['city'];
				//$_SESSION ['country'] = $row ['country'];
				//$_SESSION ['citationstyle'] = ucfirst ( $row ['citationstyle'] );
				/*$_SESSION ['styletype'] = ucfirst ( $row ['styletype'] );
				$_SESSION ['userprefs'] ['commentdisplay'] = $row ['commentdisplay'];
				$_SESSION ['userprefs'] ['defaulthome'] = $row ['defaulthome'];
				$_SESSION ['userprefs'] ['browser'] = $row ['defaultbrowser'];
				$_SESSION ['userprefs'] ['favlibrary'] = $row ['favlibrary'];
				$_SESSION ['userprefs'] ['filefolder'] = $row ['filefolder'];
				$_SESSION ['userprefs'] ['favcatalogue'] = $row ['favcatalogue'];
				$_SESSION ['userprefs'] ['faceimage'] = $row ['faceimage'];
				$_SESSION ['userprefs'] ['showhelp'] = $row ['showhelp'];*/
				$now = date ( 'Y-m-d H:i:s' );
				mysql_query ( "update cms_users set lastactive='" . $now . "' where userid='" . $userstr . "'" );
			}
		}
	}
} else {
	$_SESSION ['citationstyle'] = "Chicago";
	$_SESSION ['styletype'] = "Notation";
	$_SESSION ['country'] = "Canada";
	$_SESSION ['zippostalcode'] = "H3A2T6";
	$_SESSION ['profile'] = "0";
}
$_SESSION ['currentpage'] = "http://" . $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
if (empty ( $_SESSION ['dataset'] )) {
	$_SESSION ['dataset'] = 'all';
}

?>

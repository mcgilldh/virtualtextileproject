<?php $thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=utf-8");
//print "hi";
//print_r($_POST);
if($_SESSION['user']) {
	if(empty($wherestr)){
		$wherestr=$cms_url;
	}
	if ($_SESSION['currentpage']!=$cms_url."/login/"){
		header("Location: ".$_SESSION['currentpage']);
	}

	if ($_POST['whereurl']=="http%3A%2F%2Fwww.".$cms_domain."%2Fauth.php%3Fe%3Dv"){
		header("Location: ".$cms_url);
	}else{
		header("Location: ".$wherestr);
	}
}else{

if($_POST['agree']==1){
	mysql_query("update cms_users set agree='1' where userid='" . $_POST['user'] . "'");
}

//Are we attempting to login?
if (!empty($_POST['user']) && !empty($_POST['password'])) {
	$postinfo="full"; //we have all the data we need

	//Changing the password
	/*if($_POST['pwdchange']=="y"){
		mysql_query("update cms_users set password='" . sha1($_POST['password']) . "',pwddate=CURDATE() where userid='" . $_POST['user'] . "'");
		$authenticate=TRUE;
		$_SESSION['user']=$userstr;
		$_SESSION['password']=sha1($passwordstr);
		$_SESSION['profile']=$profilestr;
		$wherestr=$_POST['whereurl'];

		if ($wherestr=="index") header("Location: ".$cms_url);
		else header("Location: ".$wherestr);
	}*/

	//Fetch the user information from the database
	$rs = mysql_query("Select userid,password,pwddate,agree,profile,lockuser,hashid,userinterview from cms_users where userid='" . $_POST['user'] . "' limit 0,1");

	//In case the user doesn't exist in the database, fail.
	if (empty($rs)){
		$authenticate=FALSE;
		$processcase="not_a_user";
	}
	//Otherwise, we're in business.
	else {
		//For each row in the retrieved data...
		while($row = mysql_fetch_assoc($rs)){
			//Assign variables for easy access
			$userstr=$row['userid'];
			$passwordstr=$row['password'];
			$agreestr=$row['agree'];
			$profilestr=$row['profile'];
			$hashidstr=$row['hashid'];
			$userinterview=$row['userinterview'];
			//If the passwords don't match, don't even try.
			if (sha1($_POST['password'])!=$passwordstr){
				$authenticate=FALSE;
				$processcase="wrongpwd";
			}
			//If they do match, and if agree=1, and we're not locked...
			elseif (sha1($_POST['password'])==$passwordstr) {
			//assume for now that everything is good:
				$processcase="clear";
				$authenticate=TRUE; //It worked! Authenticated!
				$_SESSION['currentpage']=$_POST['whereurl'];
					//Store the user's information in this session
					$_SESSION['user']=$userstr;
					$_SESSION['hash']=$hashidstr;
					$_SESSION['profile']=$profilestr;
					$_SESSION['agree']=$agreestr;

					/*==== Create User-folders====
					if($_SESSION['user']){
						$images_dir = 'docs/'.$_SESSION['user'].'/images/';
						$files_dir = 'docs/'.$_SESSION['user'].'/files/';

						//Create the user's files directory if it doesn't exist
						if(!is_dir($files_dir)){
							mkdir($files_dir, 0764, true);
						}
						//Create the user's images directory if it doesn't exist
						if(!is_dir($images_dir)){
							mkdir($images_dir, 0764, true);
						}
					}====*/
					//We're finished with the database.
					mysql_close($con);

					//Are we supposed to remember this user's login?
					if($_POST['remember']=="yes" ){
						//Set the cookie to expire a year from now.
						$expire=time()+60*60*24*365;
						setcookie("vtp", $_SESSION['hashid'], $expire, "/",".".$cms_domain);
					}
					header("Location: ".$_SESSION['currentpage']);
//Is this a home page?

//What is this?
/*user interview for settings etc.*/
					/*
					if($userinterview==0){
						header("Location: ".$cms_url."/account/interview/");
					}
					else{
						if ($wherestr=="index"){
							header("Location: ".$cms_url);
						} else {
							header("Location: ".$wherestr);
						}
					}*/

			}
			//If the password update field is July 1, 2013, we need to renew the password
			if(strtotime($row['pwddate'])<=strtotime('2013-07-01')){
				$processcase="renew_password";
			}
			//If we don't agree with the polices governing the website, fail.
			if($row['agree']==0 && $row['lockuser']==0){
				$processcase="agree";
			}
			//If the account is locked, fail.
			if($row['lockuser']==1){
				$processcase="locked";
			}
		}
	}
}else{
	//Nothing here, so we fail.
	$postinfo="empty";
	$authenticate=FALSE;
}

//redirections as needed for outcomes
switch($postinfo){
	case "empty":
		if(chkforquerystring($s)){
			header("Location: http://".$cms_domain."&msg=login_fail");
			echo "nope.";
		}else{

			header("Location: http://".$cms_domain."?msg=login_fail");
			echo "nope.";
		}
		break;
	case "full":
		if($authenticate){
			switch($processcase){
				case "locked":
					header("Location: /account/?msg=locked");
					break;
				case "agree":
					header("Location: /account/?msg=agree");
					break;
				case "renew_password":
					header("Location: /account/?msg=pwd");
					break;
				case "clear":
					header("Location: ".$_SESSION['currentpage']);
					break;
			}
		}else{
			switch($processcase){
				case "not_a_user":
					if(chkforquerystring($s)){
						header("Location: ".$_SESSION['currentpage']."&msg=login_nu");
					}else{
						header("Location: ".$_SESSION['currentpage']."?msg=login_nu");
					}
					break;
				case "wrongpwd":
					if(chkforquerystring($s)){
						header("Location: ".$_SESSION['currentpage']."&msg=login_wp");
					}else{
						header("Location: ".$_SESSION['currentpage']."?msg=login_wp");
					}
					break;
			}
		}
	break;
}
}?>
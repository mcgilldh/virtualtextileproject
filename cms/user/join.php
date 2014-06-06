<?php $thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header("Content-Type: application/json");

if($_POST['age']=='over') {
	if(empty($_POST['lastname'])){
		$lastnameformstr="NULL";
	}else{
		$lastnameformstr= "'".ucfirst($_POST['lastname'])."'";
	}
	if(empty($_POST['firstname'])){
		$firstnameformstr="NULL";
	}else{
		$firstnameformstr= "'".ucfirst($_POST['firstname'])."'";
	}
	if(empty($_POST['middlename'])){
		$middlenameformstr="NULL";
	}else{
		$middlenameformstr= "'".ucfirst($_POST['middlename'])."'";
	}
	if(empty($_POST['email'])){
		$emailformstr="NULL";
	}else{
		$emailformstr= "'".mysql_real_escape_string($_POST['email'])."'";
	}

	if($_POST['verif_box']==$_SESSION['formverification']){

		$chkusers=mysql_query("select email from cms_users where email=".$emailformstr);
		if(mysql_num_rows($chkusers)){
			$exists=true;
		}else{

			//user with email doesn't exist, check for userids, and suggest appropriate ID
			if($_POST['lastname'] && $_POST['firstname']){
				//check in case form validation didn't work

				//strip out diacritics
				$specchars = mb_str_split("ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ ");
				$replchars = mb_str_split("SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy_");
				$stringLength= count($specchars);

				$firstname=$_POST['firstname'];
				for ($i = 0; $i < $stringLength; $i++){
					$firstname=str_replace($specchars[$i],$replchars[$i],$firstname);
				}

				$firstname = str_replace("[^A-Za-z0-9_]", "", $firstname);
				$firstname = str_replace(".", "", $firstname);
				$lastname=$_POST['lastname'];
				for ($i = 0; $i < $stringLength; $i++){
					//print $specchars[$i]." ".$replchars[$i]."-";
					$lastname=str_replace($specchars[$i],$replchars[$i],$lastname);
				}
				$lastname = str_replace("[^A-Za-z0-9_]", "", $lastname);
				$lastname = str_replace(".", "", $lastname);
				$middlename=$_POST['middlename'];
				for ($i = 0; $i < $stringLength; $i++){
					$middlename=str_replace($specchars[$i],$replchars[$i],$middlename);
				}
				$middlename = str_replace("[^A-Za-z0-9_]", "", $middlename);
				$middlename = str_replace(".", "", $middlename);
				//print "select userid from cms_users where lastname='".$_POST['ln']."' and firstname='".$_POST['fn']."'";
				$chkusers=mysql_query("select userid from cms_users where lastname='".$_POST['lastname']."' and firstname='".$_POST['firstname']."'");
				if(mysql_num_rows($chkusers)){
					//user with this first and last names exists
					$exists=true;
					$newuser['idstatus']='exists';
					$thisuser=mysql_fetch_assoc($chkusers);
					$newuser['founduser']=$thisuser['userid'];
				}else{
					//now check for userid based on first initial and lastname

					$testid=substr($firstname,0,1).$lastname;
					$testid=str_replace("'","",$testid);
					$testid=stripslashes($testid);
					//print "select userid from cms_users where userid='".$testid."'";
					$chkusers=mysql_query("select userid from cms_users where userid='".$testid."'");
					if(mysql_num_rows($chkusers)){
						//exists, but can we use the middlename to distinguish the new userid?
						if(!empty($_POST['middlename'])){
							$newtestid=substr($firstname,0,1).substr($middlename,0,1).$lastname;
						}else{
							$newtestid=substr($firstname,0,1).$lastname.substr(sha1($firstname),0,1);
						}
						$newtestid=str_replace("'","",$newtestid);
						$newtestid=stripslashes($newtestid);
						$newchkusers=mysql_query("select userid from cms_users where userid='".$newtestid."'");
						if(mysql_num_rows($newchkusers)){
							$count=mysql_num_rows($newchkusers)+1;
							//even the middle name creates the same user.
							$newuser['idstatus']='success';
							$newuser['idclose']='yes';
							//take count of matches, add one and use as new userid
							$newuser['id']=$newtestid.$count;
							$exists=false;
						}else{
							$newuser['idstatus']='success';
							$newuser['idclose']='yes';
							$newuser['id']=$newtestid;
							$exists=false;
						}
					}else{
						$newuser['idstatus']='success';
						$newuser['id']=$testid;
						$exists=false;
					}
				}

			}else{
				$newuser['status']='fail';
			}
		}
print_r($newuser);

		if($exists){
			//write fail code here

		}else{
			//add user and send verification email to user.
		$newuser['email']=$_POST['email'];
			$agreeformstr=0;
			$userinterviewformstr=0;
			$profileformstr=1;
			$madedate=date('Y-m-d H:i:s');
			$newuserhash="'".sha1($madedate.$newuser['id'])."'";
			$pwddateformstr="'".$madedate."'";
			$random=rand(0,999);
			$passwordformstr=substr(sha1(sha1($random."a43fg")),0,10);

			$sql="INSERT INTO cms_users (userid,password,pwddate,lastname,firstname,middlename,profile,email,lockuser,hashid,madedate,agree,userinterview,bio)";
			$sql=$sql . " VALUES ";
			$sql=$sql . "('" . $newuser['id'] . "',sha1('";
			$sql=$sql . $passwordformstr . "'),";
			$sql=$sql . $pwddateformstr . ",";
			$sql=$sql . $lastnameformstr . ",";
			$sql=$sql . $firstnameformstr . ",";
			$sql=$sql . $middlenameformstr . ",";
			$sql=$sql . $profileformstr . ",";
			$sql=$sql . $emailformstr . ",0,";
			$sql=$sql . $newuserhash . ",'";
			$sql=$sql . $madedate . "',";
			$sql=$sql . $agreeformstr . ",";
			$sql=$sql . $userinterviewformstr . ",null)";


			mysql_query($sql);
			if(mysql_errno()!=0){
				$newuser['created']="fail";
				$newuser['name']=$_POST['firstname']." ".$_POST['lastname'];
			}else{
				$newuser['created']="success";
				$newuser['name']=$_POST['firstname']." ".$_POST['lastname'];
				$status=TRUE;

					$to = $newuser['email'];
					$from = "no-reply@virtualtextileproject.org";
					$headers = "From: $from";
					$subject = "Virtual Textile Project: New Login Information";
					$body="Welcome to The Virtual Textile Project!\n\nWe've created a user account for you! Here are the details:\nID: ".$newuser['id']."\nPassword: ".$passwordformstr. "\nBoth are case sensitive; please keep them confidential.\n\nTo login, please visit the website at $cms_url - the login space is at the top right of each page.  If you have any problems please contact the webmaster at web@virtualtextileproject.org.\n\nUpon logging in for the first time several things will occur - first, you'll be asked to agree to some fine print.\n\nTo change your password after logging in, visit $cms_url/account/.\n\nIf you forget your password, it can be reset using this email address at $cms_url/account/forgot/.\n\n\nPlease do not reply to this email.";
					$send = mail($to, $subject, $body, $headers);
					if($send){
						$newuser['sent']="success";
					}else{
						$newuser['sent']="failed";
					}
			}
		}
	}else{
		$newuser['create']="fail";
	}
	print json_encode($newuser);
}?>
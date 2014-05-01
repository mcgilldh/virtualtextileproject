<?php if($_GET['p']=="h"){
	$pagetogo=$cms_url;
}else{
	$pagetogo=urldecode($_GET['p']);
}
$checkexists=get_headers($pagetogo,0);
if(strpos($checkexists[0],'404')){
	$pagetogo=$cms_url;
}else{
	$pagetogo=$pagetogo;
}
if(empty($pagetogo)){
	$pagetogo=$cms_url;
}
	session_unset();
    $_SESSION = array();
    unset($_SESSION['user'],$_SESSION['profile']);
	session_destroy();
setcookie("vtp", "", time()-3600,"/",$cms_url);
if($_COOKIE['vtp']){
	setcookie("vtp", "", time()-3600,"/",$cms_url);
}
header("Location: ". $pagetogo);
?>

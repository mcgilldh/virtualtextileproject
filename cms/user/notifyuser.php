<?php include("../../../../frame/pieces/phpheader_a.php"); 
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache'); 
header("Content-Type: application/json");

if($_SESSION['user'] && $_SESSION['profile']>1){

$thisuser=$_POST['u'];
$thisemail=$_POST['e'];
$thispwd=$_POST['p'];

if($thisemail){
$to = $thisemail; 
$from = "no-reply@makingpublics.org"; 
$headers = "From: $from"; 
$subject = "Making Publics: New Login Information"; 
$body="Welcome to Making Publics!\n\nWe've created a user account for you! Here are the details:\nID: ".$thisuser."\nPassword: ".$thispwd. "\nBoth are case sensitive; please keep them confidential.\n\nTo login, please visit the website at $cms_url - the login space is at the top right of each page.  If you have any problems please contact the webmaster at web@makingpublics.org.\n\nUpon logging in for the first time several things will occur - first, you'll be asked to agree to some fine print.  Though we don't anticipate any problems, laying out some of the guidelines before you leap in is only polite.  After you've agreed, you'll be asked to take 'The Interview'.  This comprises going through a series of forms that let you check to make sure we have your details right.  You can add keywords or tags to your profile, and at the end add a research statement which will appear on your profile page.  All of this information, except for your userid, can be changed and updated via your dashboard and settings pages.  When this is complete, you can take a look around the website or take a tour from the 'Get Started' page.  The research environment offers quite a bit; we suggest looking around and getting accustomed to the layout and design as it's all meant to aid you and your research.\nIf you have questions or concerns, or want to assist in the development of the Environment, please feel free to do so!  Equally, the Environment is intuitive: the more you do, the more you add - like things in the Common Archive, Comments, or Tags - the better it gets at what it does.  Don't be shy!\n\nTo change your password after logging in, visit $cms_url/myinfo/settings/password.php.\n\nIf you forget your password, it can be reset using this email address at $cms_url/admin/forgot.php.\n\n\nPlease do not reply to this email.";
$send = mail($to, $subject, $body, $headers); 
$confirm['status']="success";
$confirm['id']=$thisuser;

}else{
$confirm['status']="fail";
$confirm['id']=$thisuser;
}
print json_encode($confirm);
}
?>
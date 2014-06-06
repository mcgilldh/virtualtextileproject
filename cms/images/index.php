<?php $thisabspath = "/Users/virtualtextileproject/Sites/workingcopy";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");
header("Content-Type: text/html; charset=utf-8");
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
// Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
// HTTP/1.1
header("Pragma: no-cache");
// NO CACHE
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
?>
<div id="modalcontent" class="w450">
<?php $textileid=$_GET['textile'];
if($_SESSION['user']){
	if($_SESSION['agree']=='1'){
if(!empty($textileid)){?>
<h4><?php print $textileid;?></h4>
<?php $getimages=mysql_query("select Textile_img_id,Img_type_cd from IMG_detail where VT_tracking like cast('".$textileid."%' as char(30)) and Img_type_cd<>'Thumbnail'",$oadbcon);
			if(mysql_num_rows($getimages)){?>
	<ul id="imagelist">
		<?php
				while($getimage=mysql_fetch_assoc($getimages)){?>
		<li><?php print $getimage['Img_type_cd'];?> <a href="http://www.virtualtextileproject.org/images/?id=<?php print $getimage['Textile_img_id'];?>" class="makebutton">view</a><a href="http://www.virtualtextileproject.org/images/?id=<?php print $getimage['Textile_img_id'];?>&d=y" class="makebutton">download</a></li>
		<?php
				}?>
	</ul>
	<p>This textile has the following images available for use. Images downloaded from the Open Access website are bound by your agreement to licensing conditions described in the Open Access Policies. If you have questions regarding Open Access Licensing, please review the policies before downloading the images. These images are not for commercial use.</p>
	<?php
			}else{
				print "There aren't any additional images for this textile!";
			}
			}else{
				print "You need a textile id!";
			}
}else{
print "You haven't agreed to the fine print yet! You'll have to agree to the licensing guidelines prior to viewing or downloading any Open Access images.";
}


}else{?>

	You only have access to thumbnails under our licensing agreements! If
	you'd like to see the fuller images, please create a user account, and
	accept the guidelines to use our Open Access images.
	<?php }?>
	</div>
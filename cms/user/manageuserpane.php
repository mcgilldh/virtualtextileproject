<?php include("../../../../frame/pieces/phpheader_a.php");  
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
if($_SESSION['user']){
switch($_GET['pane']){
case "logins":
$getusersort=mysql_query("select userid,lastactive from cms_users order by lastactive desc limit 0,30");
if(mysql_num_rows($getusersort)){?><ul class="list">
<?php while($getusersortrows=mysql_fetch_assoc($getusersort)){?><li><?php print usernamebuild("l",$getusersortrows['userid'],"n")." : ".$getusersortrows['lastactive'];?></li>
<?php }?></ul>
<?php }?>
<?php break;
case "dhusers":
$getdhsort=mysql_query("select userid from cms_users where personid=0 and userid not in ('publicmaker','admin') order by userid desc limit 0,30");
if(mysql_num_rows($getdhsort)){?><ul class="list">
<?php while($getdhsortrows=mysql_fetch_assoc($getdhsort)){?><li><?php print usernamebuild("l",$getdhsortrows['userid'],"n");?>: <?php print $getdhsortrows['userid'];?></li>
<?php }?></ul>
<?php }
break;
case "comadmin":?>
<input type="text" class="text w200" id="grabuserterm" value=""/><br />
<input type="hidden" value="" id="grabuserid" name="u"/>
<script type="text/javascript" language="javascript" charset="utf-8" >
makedoubleauto('grabuser','users');
</script><br />
<select class="text" name="p" id="userprofile">
<option value="1">Member</option>
<option value="2">Community Administrator</option>
<option value="3">Website Administrator</option>
</select><br />
<input type="button" class="makebutton" onclick="changeprofile();" value="Change Profile">
<?php
break;
case "makecomadin":
if($_SESSION['profile']>2){
mysql_query("update cms_users set profile=".$_POST['p']." where userid='" . $_POST['u'] . "'");
if(mysql_errno()!=0){
  //error msg
  }else{
  print "Success! The profile of <b>".$_POST['u']."</b> has been updated!";
}
}
break;
case "edituser":?>
<input type="text" class="text w200" id="grabuserterm" value=""/><br />
<input type="hidden" value="" id="grabuserid" name="u"/>
<script type="text/javascript" language="javascript" charset="utf-8" >
makedoubleauto('grabuser','users');
</script><br />
<input type="button" class="makebutton" onclick="edituser();" value="Edit User">
<?php
break;
}
}?>

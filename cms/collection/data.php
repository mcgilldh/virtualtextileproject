<?php /*generate html blocks for isotope container. Currently barely functional - needs complete overhaul*/

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);

$thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=utf-8");

if($_POST['id']=='all'){
	//all textiles
	$isgallery=true;
	$isotopesize="";
	$isotopelink="/collections/";
	$findimage="select Textile_img_id,IMG_hdr.VT_tracking,Textile_instance.Own_id from Textile_instance inner join IMG_hdr on IMG_hdr.Textile_inst_id=Textile_instance.Textile_inst_id inner join IMG_detail on IMG_hdr.IMG_hdr_id=IMG_detail.Textile_img_hdr_id where Img_type_cd='thumbnail' order by rand()";
}elseif(is_numeric($_POST['id'])){
	//grab by specific collection - own_id
		$isgallery=true;
		$isotopesize="";
		$isotopelink="/collections/";
		$collectionid=$_POST['id'];
		$findimage="select Textile_img_id,IMG_hdr.VT_tracking,Textile_instance.Own_id from Textile_instance inner join IMG_hdr on IMG_hdr.Textile_inst_id=Textile_instance.Textile_inst_id inner join IMG_detail on IMG_hdr.IMG_hdr_id=IMG_detail.Textile_img_hdr_id where Img_type_cd='thumbnail' and Own_id=".$_POST['id']." order by rand()";
}else{
	$isgallery=false;
	$textileid=$_POST['id'];
	$isotopesize=" width2 height2 ";
	$isotopelink="/cms/images/?textile=";
	$findimage="select Textile_img_id from IMG_detail where VT_tracking like cast('".$textileid."%' as char(30)) and Img_type_cd='thumbnail'";

	}
if($_POST['l']){
	$findimage=$findimage." limit 0,".$_POST['l'];
}
$grabinstanceimgs=mysql_query($findimage,$oadbcon);
	if(mysql_num_rows($grabinstanceimgs)){
	while($instanceimg=mysql_fetch_assoc($grabinstanceimgs)){
	if($_POST['id']=='all'){
		$findvttracking=$instanceimg['VT_tracking'];
		$thiscollection=grabinfo('cms_collections','collectionid',$instanceimg['Own_id'],'1');
		$thisisotopelink=$isotopelink.$thiscollection['collectionurl']."/";
	}elseif(is_numeric($_POST['id'])){
		$thiscollection=grabinfo('cms_collections','collectionid',$collectionid,'1');
		$thisisotopelink=$isotopelink.$thiscollection['collectionurl']."/";
		$findvttracking=$instanceimg['VT_tracking'];
	}else{
		$findvttracking=$_POST['id'];
		$thisisotopelink=$isotopelink;
	}?>
<div id="textileimg<?php print $instanceimg['Textile_img_id'];?>div"
	data-id="textileimg<?php print $instanceimg['Textile_img_id'];?>"
	data-itemtype="thumbnail" class="element thumbnail <?php print $isotopesize;?>"
	data-sortdate="<?php print $thisdate;?>"
	data-sortinfo="<?php print $images[$image];?>"
	data-category="thumbnail"><span id="textileimg<?php print $instanceimg['Textile_img_id'];?>item"
		class="name"><a href="<?php print $thisisotopelink.$findvttracking;?>" class="popmodal"><img
		src="http://www.virtualtextileproject.org/images/?id=<?php print $instanceimg['Textile_img_id'];?>"></a></span>
</div>
<?php }
	}

/*
 *
* style="width:100px;height:100px;"
$grabtype=$_POST['q'];
if($_POST['s']){
$grabstart=$_POST['s'];
}else{
$grabstart=0;
}

if($_POST['l']){
$querylimit=$_POST['l'];
}else{
$querylimit="100";
}

$querystart="select distinct archivelistid, archivelisttype from (";
		$queryend=") as archiveitems order by sortdate desc limit ".$grabstart.",".$querylimit;
if($_POST['t']){
$keywords=$_POST['t'];
$keywords=str_replace("|","','",$_POST['t']);
}

if(!empty($_POST['p'])){
$thisusergroup=explode('-',$_POST['p']);
$thisgroup=$thisusergroup[0];
$thisgroupid=$thisusergroup[1];
if($grabtype=='all'){
$grabitems="'thing','person','place','event','organization'";
}else{
if($grabtype=="people"){
$grabitems="'person'";
}else{
$grabitems="'".trim($grabtype,'s')."'";
}
}
if($thisgroup=='list'){
if($keywords){
$buildquery="select distinct archivelistid, archivelisttype from (select cms_associations.linkid as archivelistid, linkedtoitem as archivelisttype from cms_associations inner join cms_keywords as kw1 on cms_associations.linkid=kw1.linkid inner join cms_keywords as kw2 on cms_associations.linkedtoitem=kw2.linktype where linkedtoitem in
		(".$grabitems.") and root='list' and rootid=".$thisgroupid." and kw1.keyword in ('".$keywords."') ) as archiveitems limit 0,".$querylimit;
}else{
$buildquery="select distinct archivelistid, archivelisttype from (select linkid as archivelistid, linkedtoitem as archivelisttype from cms_associations where linkedtoitem in
		(".$grabitems.") and root='list' and rootid=".$thisgroupid.") as archiveitems limit 0,".$querylimit;
}
}else{
if($keywords){
$buildquery="select distinct archivelistid, archivelisttype from (select cms_associations.linkid as archivelistid, linkedtoitem as archivelisttype from cms_associations inner join cms_keywords as kw1 on cms_associations.linkid=kw1.linkid inner join cms_keywords as kw2 on cms_associations.linkedtoitem=kw2.linktype where linkedtoitem in (".$grabitems.") and root='".$thisgroup."' and rootid=".$thisgroupid." and kw1.keyword in ('".$keywords."') union select cms_associations.linkid as archivelistid, linkedtoitem as archivelisttype from cms_associations inner join cms_keywords as kw1 on cms_associations.linkid=kw1.linkid inner join cms_keywords as kw2 on cms_associations.linkedtoitem=kw2.linktype where linkedtoitem in
		(".$grabitems.") and root='list' and rootid in (select linkid from cms_associations where linkedtoitem='list' and root='".$thisgroup."' and rootid=".$thisgroupid.") and kw1.keyword in ('".$keywords."') ) as archiveitems limit 0,".$querylimit;
}else{
$buildquery="select distinct archivelistid, archivelisttype from (select linkid as archivelistid, linkedtoitem as archivelisttype from cms_associations where linkedtoitem in (".$grabitems.") and root='".$thisgroup."' and rootid=".$thisgroupid." union select linkid as archivelistid, linkedtoitem as archivelisttype from cms_associations where linkedtoitem in
		(".$grabitems.") and root='list' and rootid in (select linkid from cms_associations where linkedtoitem='list' and root='".$thisgroup."' and rootid=".$thisgroupid.") ) as archiveitems limit 0,".$querylimit;
}
}

}else{

if($grabtype=='things' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr=" things_history.madeby='".$_SESSION['user']."' and things_history.actiontypeid=1";
}
if($keywords){
$thisqstr="select things.thingid as archivelistid,'thing' as archivelisttype, things_history.actiondate as sortdate from things inner join cms_keywords on things.thingid=cms_keywords.linkid inner join things_history on things_history.thingid=things.thingid where linktype='thing' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" where".$ownerstr;
}
$thisqstr="select things.thingid as archivelistid, 'thing' as archivelisttype, things_history.actiondate as sortdate from things inner join things_history on things_history.thingid=things.thingid ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}
if($grabtype=='people' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr=" people_history.madeby='".$_SESSION['user']."' and people_history.actiontypeid=1";
}
if($keywords){
$thisqstr="select people.personid as archivelistid, 'person' as archivelisttype,people_history.actiondate as sortdate from people inner join cms_keywords on people.personid=cms_keywords.linkid inner join people_history on people_history.personid=people.personid where linktype='person' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" where".$ownerstr;
}
$thisqstr="select people.personid as archivelistid, 'person' as archivelisttype, people_history.actiondate as sortdate from people inner join people_history on people_history.personid=people.personid ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}
if($grabtype=='places' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr=" places_history.madeby='".$_SESSION['user']."' and places_history.actiontypeid=1";
}
if($keywords){
$thisqstr="select places.placeid as archivelistid, 'place' as archivelisttype, places_history.actiondate as sortdate from places inner join cms_keywords on places.placeid=cms_keywords.linkid inner join places_history on places_history.placeid=places.placeid where linktype='place' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" where".$ownerstr;
}
$thisqstr="select places.placeid as archivelistid, 'place' as archivelisttype, places_history.actiondate as sortdate from places inner join places_history on places_history.placeid=places.placeid ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}
if($grabtype=='events' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr=" events_history.madeby='".$_SESSION['user']."' and events_history.actiontypeid=1";
}
if($keywords){
$thisqstr="select events.eventid as archivelistid, 'event' as archivelisttype,events_history.actiondate as sortdate from events inner join cms_keywords on events.eventid=cms_keywords.linkid inner join events_history on events_history.eventid=events.eventid where linktype='event' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" where ".$ownerstr;
}
$thisqstr="select events.eventid as archivelistid, 'event' as archivelisttype, events_history.actiondate as sortdate from events inner join events_history on events_history.eventid=events.eventid ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}

if($grabtype=='organizations' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr=" organizations_history.madeby='".$_SESSION['user']."' and organizations_history.actiontypeid=1";
}
if($keywords){
$thisqstr="select organizations.organizationid as archivelistid, 'organization' as archivelisttype, organizations_history.actiondate as sortdate from organizations inner join cms_keywords on organizations.organizationid=cms_keywords.linkid inner join organizations_history on organizations_history.organizationid=organizations.organizationid where linktype='organization' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" where".$ownerstr;
}
$thisqstr="select organizations.organizationid as archivelistid, 'organization' as archivelisttype, organizations_history.actiondate as sortdate from organizations inner join organizations_history on organizations_history.organizationid=organizations.organizationid ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}

if($grabtype=='literals' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr=" literals_history.madeby='".$_SESSION['user']."' and literals_history.actiontypeid=1";
}
if($keywords){
$thisqstr="select literals.literalid as archivelistid, 'literal' as archivelisttype,literals_history.actiondate as sortdate from literals inner join cms_keywords on literals.literalid=cms_keywords.linkid inner join literals_history on literals_history.literalid=literals.literalid where linktype='literal' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" where".$ownerstr;
}
$thisqstr="select literals.literalid as archivelistid, 'literal' as archivelisttype, literals_history.actiondate as sortdate from literals inner join literals_history on literals_history.literalid=literals.literalid ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}

if($grabtype=='journals' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr="";
}
if($keywords){
$thisqstr="select journalid as archivelistid, 'journal' as archivelisttype,'2009-10-08 00:12:54'  as sortdate from journals inner join cms_keywords on journals.journalid=cms_keywords.linkid where linktype='journal' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" where".$ownerstr;
}
$thisqstr="select journalid as archivelistid,'journal' as archivelisttype, '2009-10-08 00:12:54'  as sortdate from journals ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}
if($grabtype=='notes' || $grabtype=='all'){
if($_SESSION['dataset']=='my'){
$ownerstr="  cms_notes.noteowner='".$_SESSION['user']."'";
}
if($keywords){
$thisqstr="select noteid as archivelistid, 'note' as archivelisttype, cms_notes.notemade as sortdate from cms_notes inner join cms_keywords on cms_notes.noteid=cms_keywords.linkid where cms_notes.category='note' and linktype='note' and keyword in ('".$keywords."') ".$ownerstr;
}else{
if(!empty($ownerstr)){
$ownerstr=" and ".$ownerstr;
}
$thisqstr="select noteid as archivelistid, 'note' as archivelisttype, cms_notes.notemade as sortdate from cms_notes where cms_notes.category='note' ".$ownerstr;
}
$queryconstr[]=$thisqstr;
}

$buildquery=implode(" union ",$queryconstr);
$buildquery=$querystart.$buildquery.$queryend;

}
//print $buildquery;
$getrecentthings=mysql_query($buildquery,$con);

if(mysql_num_rows($getrecentthings)){
while($getrecentthingsrow=mysql_fetch_assoc($getrecentthings)){
unset($insideinfo);
$selectable=true;
switch($getrecentthingsrow['archivelisttype']){
case "thing":
$insideinfo=shortthingbuild($getrecentthingsrow['archivelistid']);
$title=buildthinginfo($getrecentthingsrow['archivelistid']);
//$insideinfo=$title['sorttitle'];
$sortinfo=preg_replace("/[^a-zA-Z0-9\s]/", "", $title['sorttitle']);
$widhei="width2 height2";
$link="/archive/things/?id=".$getrecentthingsrow['archivelistid'];
break;
case "person":
$insideinfo=personnamebuild("l",$getrecentthingsrow['archivelistid'],"y");
$name=buildpersoninfo($getrecentthingsrow['archivelistid']);
$sortinfo=preg_replace("/[^a-zA-Z0-9\s]/", "", $name['lastname']);
$widhei="";
$link="/archive/people/?id=".$getrecentthingsrow['archivelistid'];
break;
case "place":
$insideinfo=placenamebuild("l",$getrecentthingsrow['archivelistid'],"n");
$name=buildplaceinfo($getrecentthingsrow['archivelistid']);
$sortinfo=preg_replace("/[^a-zA-Z0-9\s]/", "", $name['place']);
$widhei="width2";
$link="/archive/places/?id=".$getrecentthingsrow['archivelistid'];
break;
case "organization":
$insideinfo=organizationbuild("l",$getrecentthingsrow['archivelistid']);
$widhei="width2 height2";
$link="/archive/organizations/?id=".$getrecentthingsrow['archivelistid'];
break;
case "library":
$selectable=false;
$insideinfo=organizationbuild("l",$getrecentthingsrow['archivelistid']);
$widhei="width2 height2";
$link="/archive/libaries/?id=".$getrecentthingsrow['archivelistid'];
break;
case "literal":
$insideinfo=literalbuild($getrecentthingsrow['archivelistid']);
$widhei="width2 height2";
$sortinfo=$insideinfo;
break;
case "event":
$insideinfo=eventnamebuild('n',$getrecentthingsrow['archivelistid']);
//$insideinfo="event".$getrecentthingsrow['archivelistid'];
$widhei="width2 height2";
$link="/archive/events/?id=".$getrecentthingsrow['archivelistid'];
break;
case "journal":
$selectable=false;
$insideinfo=journalbuild('l','n',$getrecentthingsrow['archivelistid']);
$name=buildjournalinfo($getrecentthingsrow['archivelistid']);
$sortinfo=preg_replace("/[^a-zA-Z0-9\s]/", "", $name['journaltitle']);
$widhei="width2 height2";
$link="/archive/journals/?id=".$getrecentthingsrow['archivelistid'];
break;
case "note":
$selectable=false;
$insideinfo=notebuild("l",$getrecentthingsrow['archivelistid']);
$widhei="width2 height2";
$link="/notes/?id=".$getrecentthingsrow['archivelistid'];
break;
}

$infochars=strlen(strip_tags($insideinfo));
if($infochars<=40){
$widhei="";
}elseif($infochars>40 && $infochars<80){
$widhei="width2";
}elseif($infochars>80 && $infochars<180){
$widhei="width2 height2";
}elseif($infochars>180){
$insideinfo=substr(strip_tags($insideinfo),0,180);
$widhei="width2 height2";
}
if(!$_SESSION['user']){
$selectable=false;
}
if($_POST['g']=='yes'){
?><div id="<?php print $getrecentthingsrow['archivelisttype'].$getrecentthingsrow['archivelistid'];?>div" data-id="<?php print $getrecentthingsrow['archivelistid'];?>" data-itemtype="<?php print $getrecentthingsrow['archivelisttype'];?>" class="element <?php print $getrecentthingsrow['archivelisttype']." ".$widhei;?> grabbableitem" data-sortdate="<?php print $getrecentthingsrow['sortdate'];?>" data-sortinfo="<?php print $sortinfo;?>" data-category="<?php print $getrecentthingsrow['archivelisttype'];?>"><span id="<?php print $getrecentthingsrow['archivelisttype'].$getrecentthingsrow['archivelistid'];?>item" class="name"><?php print strip_tags($insideinfo);?></span></div><?php }else{?>
<div id="<?php print $getrecentthingsrow['archivelisttype'].$getrecentthingsrow['archivelistid'];?>div" data-id="<?php print $getrecentthingsrow['archivelistid'];?>" data-itemtype="<?php print $getrecentthingsrow['archivelisttype'];?>" class="element <?php print $getrecentthingsrow['archivelisttype']." ".$widhei;?>" data-sortdate="<?php print $getrecentthingsrow['sortdate'];?>" data-sortinfo="<?php print $sortinfo;?>" data-category="<?php print $getrecentthingsrow['archivelisttype'];?>"><a href="<?php print $link;?>" id="<?php print $getrecentthingsrow['archivelisttype'].$getrecentthingsrow['archivelistid'];?>item" class="name"><?php print strip_tags($insideinfo);?></a><span class="recent"><?php //print $getrecentthingsrow['archivelistdate'];?></span><?php if($selectable){?><span class="save"><input type="checkbox" onclick="selectthisitem('<?php print $getrecentthingsrow['archivelisttype'];?>','<?php print $getrecentthingsrow['archivelistid'];?>');" class="text" style="margin-left:3px;"/></span><?php }?></div><?php }
}
}*/

	/* not sure...
$types=array('cotton','linen','silk','wool','polyester','synthetic_blend','natural_blend','other');
$images=array('Morris_African_Marigold_printed_textile_1876.png','RB_C001_0001000001_1_GR_CR_REDUX_60.png','RB_C001_0001000002_1_GR_CR_REDEX_60.png','RB_C001_0001000008_CR2.png');
for($i=0;$i<100;$i++){
	$int= mt_rand(1262055681,1262055681);
	$thisdate = date("Y-m-d H:i:s",$int);
	$type=array_rand($types);
	$image=array_rand($images);
?><?php } */?>


<?php include("/Users/virtualtextileproject/Sites/includes/connection.php");
ini_set('display_errors', '1');
error_reporting(E_ALL | E_NOTICE);
$i=0;
$colors=mysql_query("select distinct Pantone_nm,RGB_R,RGB_B,RGB_G from color_detail where pantone_grp='Pantone'",$oadbcon);
while($color=mysql_fetch_assoc($colors)){
	$swatch[$i]['name']=$color['Pantone_nm'];
	$swatch[$i]['r']=$color['RGB_R']/255;
	$swatch[$i]['g']=$color['RGB_G']/255;
	$swatch[$i]['b']=$color['RGB_B']/255;
	$i++;
}

print json_encode($swatch);
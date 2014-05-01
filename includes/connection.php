<?php
global $oadbcon;
$oadbcon = mysql_connect($oadb_host.":".$oadb_port,$oadb_user,$oadb_passwd, TRUE) or die('Could not connect: ' . mysql_error());
mysql_select_db($oadb_name, $oadbcon);
mysql_query('set names "utf8"');

global $con;
$con = mysql_connect($db_host.":".$db_port,$db_user,$db_passwd, TRUE) or die('Could not connect: ' . mysql_error());
  mysql_select_db($db_name, $con);
  mysql_query('set names "utf8"');

?>

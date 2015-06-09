<?php
define("INC",true);
include("inc/conf.inc.php");

$id = intval($_GET['id']);

$q = mysql_query("SELECT * FROM `books` WHERE `id` = '".$id."'");
$book = mysql_fetch_array($q);

if(mysql_num_rows($q)==0){
	include("header.php");
	echo '<br /><div align="center">Sorry, the link you followed is invalid.<br /><a href="javascript:history.go(-1);">Go Back &raquo;</a></div>';
	include("footer.php");
	die();
}

if($LoggedIn and ($u['id']==$book['owner'])){
	
	if($book['paid']=='1'){
		include("header.php");
		echo '<br /><div align="center">You have already paid for this listing.<br /><a href="javascript:history.go(-1);">Go Back &raquo;</a></div>';
		include("footer.php");
		die();
	}else{
		
		die("Error");
		
	}
	
}else{
	include("header.php");
	echo '<br /><div align="center">Sorry, the link you followed is invalid.<br /><a href="javascript:history.go(-1);">Go Back &raquo;</a></div>';
	include("footer.php");
	die();
}
?>

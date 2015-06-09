<?php
define("INC",true);
include("inc/conf.inc.php");

if(!$LoggedIn){
	die(header("Location: login.php"));	
}

include("header.php");

$book = mysql_fetch_array(mysql_query("SELECT * FROM `books` WHERE `id` = '".intval($_GET['id'])."' AND `owner` = '".$u['id']."' LIMIT 1"));


if($_GET['confirm']=='true'){
	@unlink($book['image']);
	mysql_query("DELETE FROM `books` WHERE `id` = '".$book['id']."'");
	$done = true;
}


if(!$done){
?>
<div style="padding-left:30px;"><h1>Confirmation</h1></div>
<form action="delete.php?id=<?php echo intval($_GET['id']); ?>&confirm=true" method="post" enctype="multipart/form-data" name="Post">
<div align="center">Are you sure you want to delete your listing?<br>
</div>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td width="50%"><div align="right">
			<input type="button" onClick="javascript:history.go(-1);" name="button" id="button" value="Cancel">
		</div></td>
		<td><input type="submit" name="button" id="button" value="Delete"></td>
	</tr>
</table>
</form>
<?php
}else{
?>
<br /><br />
<div align="center">
	<p>The listing was successfully deleted!</p>
	<p><a href="myaccount.php">Return to My Account</a><br />
		<br />
	</p>
</div> 
<?php	
}
include_once("footer.php");
?>

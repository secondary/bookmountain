<?php define("INC",true); 
$logout = true;
include ('inc/conf.inc.php'); 
$_SESSION["user"] = '';
$_SESSION["pass"] = '';
setcookie("user", '',time()-5*3600);
setcookie("pass", '',time()-5*3600);
?>
<?php
//header("Location: ".getprevpage());
$LoggedIn = false;
?>
<?php include ('header.php'); ?><br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34" align="left"><br /></td>
    <td width="730" align="left"><img src="images/accountsLogo1.jpg" width="300" height="72" /></td>
    <td width="494" align="left">&nbsp;</td>
    <td width="80" align="left">&nbsp;</td>
  </tr>
  </table>
<div align="center"><br />
<br />
You have successfully logged out!<br /><br />
<a href="<?php echo getprevpage(); ?>">Continue &raquo;</a><br />
<br />
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php include ('footer.php'); ?>

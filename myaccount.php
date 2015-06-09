<?php
define("INC",true);
include("inc/conf.inc.php");

if(!$LoggedIn){
	die(header("Location: login.php"));	
}

?>
<?php
include("header.php");
?>

<br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34" align="left"><br /></td>
    <td width="730" align="left"><a href="index.php"></td>
    <td width="494" align="left">&nbsp;</td>
    <td width="80" align="left">&nbsp;</td>
  </tr>
  <tr>
   <td align="left" valign="top"> <h1>&nbsp;</h1></td>
    <td align="left" valign="top"><h1>My Account</h1>
<span class="create"><a href="updateacc.php">Update Account</a></span><br /><br />
<div style="border:1px solid #C3D9FF; background-color:#E8EEFA; width:710px;">
    		<div style="border:3px solid #ffffff;">
<table width="699" border="0" cellpadding="5" cellspacing="0">
	    <tr>
          <td width="158"><div align="right"><strong>First Name:</strong></div></td>
          <td width="521" align="left"><?php echo stripslashes($u['firstname']); ?></td>
        </tr>
		  <tr>
          <td width="158"><div align="right"><strong>Last Name:</strong></div></td>
          <td width="521" align="left"><?php echo stripslashes($u['lastname']); ?></td>
        </tr>
        <tr>
          <td width="158"><div align="right"><strong>Email address:</strong></div></td>
          <td width="521" align="left"><?php echo stripslashes($u['email']); ?></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Password:</strong></div></td>
          <td align="left">******** <a href="newpass.php?id=<?php echo $u['id']; ?>&key=<?php echo $u['key']; ?>">(Change)</a></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Location:</strong></div></td>
          <td align="left"><?php echo $u['country']; ?></td>
        </tr>
      </table></div>
	  </div>
	  <h1>Books I'm Selling</h1>
	  <div style="border:1px solid #C3D9FF; background-color:#E8EEFA; width:710px;">
    		<div style="border:3px solid #ffffff;">
			<?php
	$q = mysql_query("SELECT * FROM `books` WHERE `owner` = '".$u['id']."' AND `sold` = '0' AND `date` > ".(time()-(30*86400))."");
	if(mysql_num_rows($q)!=0){
		?>
			<table width="699" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td width="358"><strong>Title</strong></td>
		<td width="88"><div align="center"><strong>Price</strong></div></td>
		<td width="233"><div align="right"><strong>Options</strong></div></td>
	</tr>
	<?php
	while($r=mysql_fetch_array($q)){
		if(!$r['paid']) $pay = ' | <a href="payforlisting.php?id='.$r['id'].'">Pay &raquo;</a>'; else $pay = '';
		
	echo '<tr>
		<td class="row">'.shorten($r['title'],60).'</td>
		<td class="row"><div align="center">$'.number_format($r['price'],2,'.',',').'</div></td>
		<td class="row"><div align="right"><a href="details.php?id='.$r['id'].'">View</a> | <a href="edit.php?id='.$r['id'].'">Edit</a> | <a href="delete.php?id='.$r['id'].'">Delete</a>'.$pay.'</div></td>
	</tr>';
	}
	?>
</table>
<?php
	}else{
		echo '<div align="center">Your are not selling any books. Would you like to <a href="sell.php">sell</a> one?</div>';	
	}
	?>
			</div>
	  </div>
<!-- Books I've Sold and Book I've Purchased are taken off--->	 



	</div>
	  </div>	
</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
</table>
<br><br>
<?php
include("footer.php");
?>

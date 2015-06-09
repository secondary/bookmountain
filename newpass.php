<?php
define("INC",true);
include("inc/conf.inc.php");
?>
<?php include ('header.php'); ?>
<br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34" align="left"><br /></td>
    <td width="730" align="left"></td>
    <td width="494" align="left">&nbsp;</td>
    <td width="80" align="left">&nbsp;</td>
  </tr>
<tr>
<td>&nbsp;</td>
<td>

<p><strong>Please enter your new password below</strong></p>
<div style="border:1px solid #C3D9FF; background-color:#E8EEFA;">
<div style="border:3px solid #ffffff;padding:5px 5px 5px 5px;">
<?php

 $q = query("SELECT * FROM `users` WHERE `key` = '".escape_data($_GET['key'])."' AND `id` = '".intval($_GET['id'])."'");
			  
			  if(mysql_num_rows($q)==0){
				  echo '<br /><div align="center"><br />Sorry, the link you followed was invalid.<br />
<br /><a href="index.php">Return Home</a><br /></div><br />';  
			  }else{



		  if($_GET['do']=='change'){
			  $post['password'] = $_POST['password'];
				$post['password2'] = $_POST['password2'];
	
			  if($post['password'] !=$post['password2']){
				$errors[] = 'Your password\'s don\'t match.';
				}elseif(strlen($post['password'])<6){
				$errors[] = 'Your password is too short, make sure it\'s atleast 6 characters in length.';	
				}
			  
			  if(!$errors){
				$sent = true;
				$key = md5(time().$post['email']).sha1(base64_encode(time()));
				query("UPDATE `users` SET `password` = '".sha1($post['password'])."', `key` = '".$key."' WHERE `key` = '".$_GET['key']."' AND `id` = '".$_GET['id']."'");
				$_SESSION['pass'] = escape_data(sha1($post['password']));
				$sent = true;
			  }
			  
		  }
		  
if(!$sent){
			  
		if($errors){
				echo '<strong>The following errors occurred while changing your password:</strong> <ul>';
			foreach($errors as $err){
							echo '<li>'.$err.'</li>';	
			}
				echo '</ul>';
		}
		  ?>
          <form action="newpass.php?do=change&id=<?php echo $_GET['id']; ?>&key=<?php echo $_GET['key']; ?>" method="post" enctype="application/x-www-form-urlencoded" name="accountinfo">

          <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr>
              <td width="42%" align="right" class="right">New Password: *</td>
              <td width="58%" align="left"><input type="password" name="password" id="pass" size="30"  value="" /></td>
            </tr>
             <tr>
              <td width="42%" align="right" class="right">Repeat Password: *</td>
              <td width="58%" align="left"><input type="password" name="password2" id="pass" size="30"  value="" /></td>
            </tr>
            <tr>
              <td class="right" colspan="2"><div align="right"><em>* Required</em></div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                <input type="submit" name="submit" id="submit" value="Submit" />
              </div></td>
              </tr>
          </table>
      </form>
          <?php
		  }else{
			echo '<br />
<div align="center"><br />Your password was successfully changed.<br />
<br /><a href="index.php">Return Home</a><br /></div><br />';  
		  }
		  }
		  ?>
        </div>
    </div></td>
          
	  <td>&nbsp;</td>
		   <td>&nbsp;</td>
		  </td>
</table>
<br><br><br><br><br><br><br><br><br>
<?php include ('footer.php'); ?>

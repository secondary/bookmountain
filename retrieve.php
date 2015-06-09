<?php
define("INC",true);
include("inc/conf.inc.php");
?>
<?php include ('header.php'); ?>
<br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34" align="left"><br /></td>

    <td width="494" align="left">&nbsp;</td>
    <td width="80" align="left">&nbsp;</td>
  </tr>
<tr>
<td>&nbsp;</td>
<td>
<h1 class="style4">Problems Logging</h1>
<div style="border:1px solid #C3D9FF; background-color:#E8EEFA;">
<div style="border:3px solid #ffffff;padding:5px 5px 5px 5px;">
<?php
		  if($_GET['do']=='send'){
			  $post['email'] = $_POST['email'];
	
			  if(!$post['email']){
				  $errors[] = 'Please fill in your email.';
			  }elseif(!email_valid($post['email'])){
				  $errors[] = 'Your email appears to be invalid.';
			  }else{
			  
			  $q = query("SELECT * FROM `users` WHERE `email` = '".$post['email']."'");
			  
			  if(mysql_num_rows($q)==0){
				  $errors[] = 'The email you entered is not registered on BookMountains.';
			  }
			  
			  }
			  
			  if(!$errors){
				$sent = true;
				$key = md5(time().$post['email']).sha1(base64_encode(time()));
				query("UPDATE `users` SET `key` = '".$key."' WHERE `email` = '".$post['email']."'");
				
				$emailmsg = '<html>
<head>
  <title>Retrieve Account Information</title>
</head>
<body>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
 <tr>
 <td colspan="2">Your accounts are listed below:</td>
 </tr>';
	 $q = query("SELECT * FROM `users` WHERE `email` = '".$post['email']."'");
	while($me=mysql_fetch_array($q)){
		
	$emailmsg .= '<tr>
    <td width="20%">'.$me['firstname'].' '.$me['lastname'].'</td>
    <td width="80%"><a href="'.$conf['domain'].'/newpass.php?key='.$key.'&id='.$me['id'].'">Change Password</a></tr>';	
	
	}
	
$emailmsg .='<tr>
 <td colspan="2">'.$conf['footer'].'</td>
 </tr></table>
</body>
</html>';

email($conf['feedbackemail'],$post['email'],"BookMountains - Account Information",$emailmsg);

				
			  }
			  
		  }
		  
if(!$sent){
			  
		if($errors){
				echo '<strong>The following errors occurred while sending your message:</strong> <ul>';
			foreach($errors as $err){
							echo '<li>'.$err.'</li>';	
			}
				echo '</ul>';
		}
		  ?>
          
          <div align="left">If you're having any problems logging in, just enter your email below and we'll email your account information to you.<br />
            <br />
          </div>
    <form action="retrieve.php?do=send" method="post" enctype="application/x-www-form-urlencoded" name="accountinfo">

          <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr>
              <td width="24%" class="right"><div align="right">Your Email: *</div></td>
              <td width="76%"><div align="left">
                <input type="text" name="email" id="email" size="50"  value="<?php echo stripslashes(htmlspecialchars($_POST['email']));  ?>" />
              </div></td>
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
			echo '<br /><div align="center"><br />We\'ve sent your account information to the email on file. If it does not arrive within 15-20 minutes, please be sure to check your spam folder.<br />
<br /><a href="index.php">Return Home</a><br /></div><br />';  
		  }
		  ?>
          </div>
          </div></td>
		  <td>&nbsp;</td>
		   <td>&nbsp;</td>
		  </td>
</table>
<br><br><br><br><br><br>
<?php include ('footer.php'); ?>

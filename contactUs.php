<?php

include("inc/conf.inc.php");
include("header.php");
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34" align="left"></td>
    <td width="906" align="left"><br />      <img src="images/accountsLogo1.jpg" width="300" height="72" /></td>
    <td width="598" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top"> <h1>&nbsp;</h1></td>
    <td align="left" valign="top"><h1>Contact Us</h1>
          
          <?php
		  if($_GET['do']=='send'){
			  $post['name'] = htmlspecialchars(stripslashes($_POST['name']));
			  $post['email'] = $_POST['email'];
			  $post['subject'] = htmlspecialchars(stripslashes($_POST['subject']));
			  $post['message'] = htmlspecialchars(stripslashes($_POST['message']));
			   $post['phone'] = htmlspecialchars(stripslashes($_POST['phone']));
			  
			  if(!$post['name']){
				  $errors[] = 'Please fill in your name.';
			  }
			  if(!$post['email']){
				  $errors[] = 'Please fill in your email.';
			  }elseif(!email_valid($post['email'])){
				  $errors[] = 'Your email appears to be invalid.';
			  }
			  if(!$post['subject']){
				  $errors[] = 'Please fill in the subject.';
			  }
			  if(!$post['message']){
				  $errors[] = 'Please enter a message.';
			  }
			  
			  foreach($post as $key => $val){
				$post[$key] = stripslashes($val);  
			  }
			  
			  
			  if(!$errors){
				$sent = true;
				
				$emailmsg = '<html>
<head>
  <title>'.$post['subject'].'</title>
</head>
<body>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="5%">Name:</td>
    <td width="95%">'.$post['name'].'</td>
  </tr>
  <tr>
    <td>Email:</td>
    <td>'.$post['email'].'</td>
  </tr>
    <tr>
    <td>Phone:</td>
    <td>'.phone($post['phone']).'</td>
  </tr>
  <tr>
    <td colspan="2">'.$post['message'].'</td>
  </tr>
</table>
</body>
</html>';



				
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
          <form action="contactUs.php?do=send" method="post" enctype="application/x-www-form-urlencoded" name="contact">
<div style="border:1px solid #C3D9FF; background-color:#E8EEFA;">
<div style="border:3px solid #ffffff;">
          <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr>
              <td width="24%" class="right"><div align="right">Your Name: *</div></td>
              <td width="76%"><div align="left">
                <input name="name" type="text" id="name" value="<?php  if($u) echo $u['firstname'].' '.$u['lastname']; else echo stripslashes(htmlspecialchars($_POST['name'])); ?>" size="50" />
              </div></td>
            </tr>
            <tr>
              <td class="right"><div align="right">Your Email: *</div></td>
              <td><div align="left">
                <input type="text" name="email" id="email" size="50"  value="<?php if($u) echo $u['email']; else echo stripslashes(htmlspecialchars($_POST['email']));  ?>" />
              </div></td>
            </tr>
			<tr>
              <td class="right"><div align="right">Phone:</div></td>
              <td><div align="left">
                <input type="text" name="phone" id="subject" size="50"  value="<?php echo stripslashes(htmlspecialchars($_POST['phone'])); ?>" />
              </div></td>
            </tr>
            <tr>
              <td class="right"><div align="right">Subject: *</div></td>
              <td><div align="left">
                <input type="text" name="subject" id="subject" size="50"  value="<?php echo stripslashes(htmlspecialchars($_POST['subject'])); ?>" />
              </div></td>
            </tr>
            <tr>
              <td class="right"><div align="right">Message: *</div></td>
              <td><div align="left">
                <textarea name="message" cols="50" rows="3" id="message"><?php echo stripslashes(htmlspecialchars($_POST['message'])); ?></textarea>
              </div></td>
            </tr>
            <tr>
            <td class="right" colspan="2"><div align="right"><em>* Required</em></div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                <input type="submit" name="submit" id="submit" value="Send" />
              </div></td>
              </tr>
          </table>
		  </div>
		  </div>
          </form>
          <?php
		  }else{
			echo '<br /><br /><div align="right"><br />
Your message was successfully sent. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />
<a href="index.php">Return Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
<br /><br><br><br><br><br><br><br></div>';  
		  }
		  ?></td>
    <td align="left">&nbsp;<div style="width:50px;"></div></td>
  </tr>
</table>
<br>
<?php include ('footer.php'); ?>


<?php
define("INC",true);
include("inc/conf.inc.php");
include("header.php");

if(!$LoggedIn){
	die(header("Location: login.php"));	
}

$e = new error_class;
$e->setcaption("<strong>The following errors occurred while registering:</strong>");


if($_GET['do']=='register'){
	$post['fname'] = $_POST['fname'];
	$post['lname'] = $_POST['lname'];
	$post['email'] = $_POST['email'];
	$post['pass1'] = $_POST['pass1'];
	$post['pass2'] = $_POST['pass2'];
	$post['country'] = $_POST['country'];
	$post['captcha'] = $_POST['captcha'];
	
	
	if(!$post['fname']){
		$e->adderror("Please enter your first name.");
	}
	if(!$post['lname']){
		$e->adderror("Please enter your last name.");
	}
	
	if(!$post['email']){
		$e->adderror("Please enter your email.");
	}elseif(!email_valid($post['email'])){
		$e->adderror("The email you entered is invalid.");
	}elseif(mysql_num_rows(mysql_query("SELECT * FROM `users` WHERE `email` = '".$post['email']."'"))!=0 and $u['email']!=$_POST['email']){
		$e->adderror("The email you entered is already registered.");
	}
	
	if($post['captcha']!=$_SESSION['captcha'] or !$post['captcha']){
		$e->adderror("The verification code you entered is invalid. ".$post['captcha'].' '.$_SESSION['captcha']);
	}
	
	if($e->containserrors()==false){
		
		
		query("UPDATE `users` SET `email` = '".$post['email']."', `firstname` = '".escape_data($post['fname'])."', `lastname` = '".escape_data($post['lname'])."', `country` = '".escape_data($post['country'])."', `key` = '".sha1(time())."' WHERE `id` = '".$u['id']."' ");
		$done = true;
		
		$_SESSION['user'] = escape_data($post['email']);
		
	}
	
}

?>

<form action="updateacc.php?do=register" method="post" enctype="application/x-www-form-urlencoded" name="register">
<br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="38" align="left"><br /></td>
    <td colspan="2" align="left"></td>
    <td width="333" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5" align="left" valign="top"> <h1>&nbsp;</h1></td>
    <td colspan="2" align="left" valign="top">
	<?php
	if($e->containserrors()){
		echo '<div style="padding:5px 5px 5px 5px;">';
		$e->displayerrors();
		echo '</div>';
	}
	?>
    
	 </td>
    <td rowspan="5" align="left"><div align="left">&nbsp;
    </div>
      <div style="width:50px;"></div></td>
  </tr>
      <td height="100" colspan="4">
	  <?php
	  if(!$done){
		  ?>
	   <div align="left"><h1> Required Information</h1></div>
	   <div style="border:1px solid #C3D9FF; background-color:#E8EEFA; width:710px;">
    		<div style="border:3px solid #ffffff;">
	  <table width="699" border="0" cellpadding="5" cellspacing="0">
	    <tr>
          <td width="158"><div align="right"><strong>First Name:</strong></div></td>
          <td width="521" align="left"><label>
            <input name="fname" type="text" id="email" size="35" value="<?php echo stripslashes($u['firstname']); ?>" />
          </label></td>
        </tr>
		  <tr>
          <td width="158"><div align="right"><strong>Last Name:</strong></div></td>
          <td width="521" align="left"><label>
            <input name="lname" type="text" id="email" size="35" value="<?php echo stripslashes($u['lastname']); ?>" />
          </label></td>
        </tr>
        <tr>
          <td width="158"><div align="right"><strong>Email address:</strong></div></td>
          <td width="521" align="left"><label>
            <input name="email" type="text" id="email" size="35" value="<?php echo stripslashes($u['email']); ?>" />
          </label></td>
        </tr>
        <tr>
        	<td colspan="2"><hr /></td>
        	</tr>
        <tr>
          <td><div align="right"><strong>Location:</strong></div></td>
          <td><div align="left">
            <select name="country" id="country">
              <?php
                $str = '<option value="US">United States</option>
	<option value="CA">Canada</option>
	<option value="AT">Austria</option>
	<option value="BE">Belgium</option>
	<option value="FR">France</option>
	<option value="DE">Germany</option>
	<option value="LU">Luxembourg</option>
	<option value="NL">Netherlands</option>
	<option value="CH">Switzerland</option>
	<option value="UK">United Kingdom</option>
	<option value="AR">Argentina</option>
	<option value="AU">Australia</option>
	<option value="BR">Brazil</option>
	<option value="CL">Chile</option>
	<option value="CN">China</option>
	<option value="CZ">Czech Republic</option>
	<option value="DK">Denmark</option>
	<option value="EG">Egypt</option>
	<option value="FI">Finland</option>
	<option value="GR">Greece</option>
	<option value="HK">Hong Kong</option>
	<option value="HU">Hungary</option>
	<option value="IN">India</option>
	<option value="ID">Indonesia</option>
	<option value="IE">Ireland</option>
	<option value="IL">Israel</option>
	<option value="IT">Italy</option>
	<option value="JP">Japan</option>
	<option value="JO">Jordan</option>
	<option value="KR">Korea</option>
	<option value="KW">Kuwait</option>
	<option value="MY">Malaysia</option>
	<option value="MX">Mexico</option>
	<option value="NZ">New Zealand</option>
	<option value="NO">Norway</option>
	<option value="PL">Poland</option>
	<option value="PT">Portugal</option>
	<option value="SG">Singapore</option>
	<option value="SI">Slovenia</option>
	<option value="ZA">South Africa</option>
	<option value="KP">South Korea</option>
	<option value="ES">Spain</option>
	<option value="SE">Sweden</option>
	<option value="TW">Taiwan</option>
	<option value="TH">Thailand</option>
	<option value="VN">Vietnam</option>';
	if(!$u['country']) $data['country'] = 'US'; else $data['country'] = $u['country'];
	
	
				$str = str_replace('"'.$data['country'].'"','"'.$data['country'].'" selected="selected"',$str);
				echo $str;
                ?>
            </select>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Word Verification:</strong></div></td>
          <td align="left"><p> Type the characters you see in the picture below.<br />
            
            <label>
              <input type="text" name="captcha" id="capture" value="" />
            </label>
            <br />
          Letters are not case-sensitive</p></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td><div align="left">
        	  <input type="submit" name="submit" id="submit" value="Update" />
      	  </div></td>
        	</tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
      </table>
	  
	  <?php
}else{
?><br><br>
<div align="center">You have successfully updated your account information.<br><br>
<a href="<?php echo getprevpage(); ?>">Continue &raquo;</a></div>
<br><br><br><br><br><br><br><br><br><br><br><br>
<?php	
}
?>
	 </div>
	 </div>
	  </td>
    </tr>
</table>
</form>
<br><br>
<?php	
include_once("footer.php");
?>

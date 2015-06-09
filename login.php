<?php
define("INC",true);
include("inc/conf.inc.php");

$e = new error_class;
$e->setcaption("<strong>Error:</strong>");

if($_GET['do']=='login'){
	$post['email'] = $_POST['email'];
	$post['pass'] = $_POST['pass'];

$n = mysql_num_rows(mysql_query("SELECT * from `users` where `email` = '".escape_data($post['email'])."' AND `password` = '".escape_data(sha1($post['pass']))."'"));
	
	if(!$post['email']){
		$e->adderror("Please enter your email.");
	}elseif(!email_valid($post['email'])){
		$e->adderror("The email you entered is invalid.");
	}
	
	if(!$post['pass']){
		$e->adderror("Please enter a password.");
	}
	
	
	if($n!=1){
	$e->adderror("The username or password you entered is invalid.");
	}
	
	if($e->containserrors()==false){
	$valid = true;
	
	if($_POST['remember']=='1'){
		setcookie("user", $post['email'],time()+5*3600);
  		setcookie("pass", sha1($post['pass']),time()+5*3600);
	}
	
	$_SESSION['user'] = escape_data($post['email']);
	$_SESSION['pass'] = escape_data(sha1($post['pass']));
	
	die(header("Location: ".getPrevpage()));
	}

}

include("header.php");
?>

<br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="left"><br /></td>
    <td width="679" align="left"></td>
    <td width="358" align="left">&nbsp;</td>
    <td width="198" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top"> <h1>&nbsp;</h1></td>
    <td colspan="3" align="left" valign="top">
    	<br>
    	<div style="border:1px solid #C3D9FF; background-color:#E8EEFA; width:360px;">
    		<div style="border:3px solid #ffffff;">
    			<form action="login.php?do=login" method="post" enctype="application/x-www-form-urlencoded" name="login">
    				<table width="350" border="0" align="center" cellpadding="5" cellspacing="0">
    					<tr>
    						<td colspan="2" align="center">Sign in with your <br />
    							<strong class="fourTeen">BookMountains Account<br /></strong>
    							<?php
	if($e->containserrors()){
		echo '<div style="padding:5px 5px 5px 5px;" align="left">';
		$e->displayerrors();
		echo '</div>';
	}
	?></td>
    						</tr>
    					<tr>
    						<td width="130" align="right">Email:</td>
    						<td width="200" align="left"><input name="email" type="text" id="email" size="35" /></td>
    						</tr>
    					<tr>
    						<td align="right">Password:</td>
    						<td align="left"><input name="pass" type="password" id="password" size="35" /></td>
    						</tr>
    					<tr>
    						<td align="right"><input name="remember" type="checkbox" id="remember" value="1" /></td>
    						<td align="left">Stay signed in</td>
    						</tr>
    					<tr>
    						<td colspan="2" align="right"><div align="center"><input type="submit" name="submit" id="submit" value="Sign In" /> 
    							<a href="signUp.php">Create Account</a></div></td>
    						</tr>
    					<tr>
    						<td colspan="2" align="center"><a href="retrieve.php">Having problems signing in?</a></td>
    						</tr>
    					</table>
    				</form>
    			</div>
    		</div>    	&nbsp;<div style="width:50px;"></div></td>
    </tr>
  
</table>
<br><br><br>
<?php
include_once("footer.php");
?>

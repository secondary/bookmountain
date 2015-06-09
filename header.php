<html>
<head>
<style type="text/css">
<!--
#topMenu {
	height: 32px;
	width: 100%;
	margin-bottom:10px;
}

body {
	background-color: #FFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	/* background-image:url(images/bg_pat.gif);
	background-repeat:repeat-x; */
	width:100%;
}

body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
}
-->
</style>

</head>
<body>

<div id="topMenu">
  <div style="text-align:right; padding-top:6px; padding-right:10px;"><?php 
  	  if($LoggedIn){
		  echo 'Welcome! <strong>'.$u['email'].'</strong> | <a href="myaccount.php">My Account</a>'; 
		  if(!$hide['buy']) echo ' | <a href="index.php">Buy</a>';
		  echo ' | <a href="sell.php">Sell</a> | <a href="logout.php">Sign Out</a>'; 
 	  }else{ 
		  if(!$hide['buy']) echo '<a href="index.php">Buy</a> | ';
		  echo '<a href="sell.php">Sell</a> | <a href="login.php">Login</a>'; 
	  }
	  ?></div>
</div>

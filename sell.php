<?php
define("INC",true);
include("inc/conf.inc.php");

if(!$LoggedIn){
	die(header("Location: login.php"));	
}
die(header("Location: sellersPage.php"));	

include("header.php");
?>
<form action="sellersPage.php?do=lookup" method="post" enctype="application/x-www-form-urlencoded" name="lookup">
<br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="38" align="left"><br /></td>

    <td width="333" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5" align="left" valign="top"> <h1>&nbsp;</h1></td>
    <td colspan="2" align="left" valign="top"></td>
    <td rowspan="5" align="left">&nbsp;<div style="width:50px;"></div></td>
  </tr>
      <td height="100" colspan="4">
	  <div style="border:1px solid #C3D9FF; background-color:#E8EEFA; width:630px;">
    <div style="border:3px solid #ffffff;">
	  <table width="619" border="0" cellpadding="5" cellspacing="0">
	  <tr>
	  <td colspan="2">
	  
	  Welcome to the sellers home page. Please enter the ISBN-10 or ISBN-13 and select the book condition and we'll lookup the book information for you. Alternately, you can just click next to enter the book information yourself.<br />
     <h1> Book Lookup</h1></td>
	  </tr>
        <tr>
        	<td width="154"><div align="right"><strong>ISBN-10:</strong></div></td>
        	<td width="445"><div align="left">
        	  <input name="isbn-10" type="text" id="textfield2" size="35" /> <a href="#">What is ISBN?</a>
      	  </div></td>
        	</tr>
		<tr>
          <td><div align="right"><strong>ISBN-13:</strong></div></td>
          <td><div align="left">
            <input name="isbn-13" type="text" id="textfield2" size="35" />
          </div></td>
        </tr>
        <tr>
        	<td height="36" valign="top"><div align="right"><strong>Condition:</strong></div></td>
        	<td align="left" valign="middle"><p>
        	<input name="condition" type="radio" id="poor" value="A" checked="checked" />Like New 
        	<br />
        	<input type="radio" name="condition" id="poor2" value="B" />Very Good 
        	<br />
        	<input type="radio" name="condition" id="poor3" value="C" />Good 
			<br />
			<input type="radio" name="condition" id="poor4" value="D" />Acceptable 
			<br />
			<input type="radio" name="condition" id="poor5" value="F" />Poor
			</td>
       	  </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td><div align="left">
        	  <input type="submit" name="submit" id="submit" value="Next &raquo;" />
      	  </div></td>
        	</tr>
      </table>
	  </div>
	  </div>
	  </td>
    </tr>
</table>
</form>

<?php
include_once("footer.php");
?>

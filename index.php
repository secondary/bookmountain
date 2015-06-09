<?php
include "INC/"
include ("header.php");


?>

<form action="results.php" method="get">
<table width="619" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" align="center">BookMountain</td>
  </tr>
  <tr>
    <td height="33" colspan="2" align="center">
	<table width="71%" border="0" cellspacing="2" cellpadding="2">
    	<tr>
    			<td width="21%"><div align="right">
				<select name="searchby" id="searchby">
    				<option value="all">Search By...</option>
    				<option value="keywords">Keywords</option>
    				<option value="title">Title</option>
    				<option value="author">Author</option>
    				<option value="isbn">ISBN</option>
    				</select>
					</div></td>
    			<td width="54%"><input name="query" type="text" id="searchField" size="55" /></td>
    			<td width="10%"><div align="right"><input type="submit" name="submit" id="submit" value="Search" /></div></td>
    			</tr>
    		</table></td>
  </tr>
    <tr>
    <td height="101" colspan="2" align="center">&nbsp;</td>
  </tr>
    <tr>
</table>
</form>
<br><br><br><br><br><br><br><br>

<?php

include ("footer.php");
?>

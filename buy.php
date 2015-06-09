<?php
define("INC",true);
include("inc/conf.inc.php");

if(!$LoggedIn){
	die(header("Location: login.php"));	
}

$id = intval($_GET['id']);

$q = mysql_query("SELECT * FROM `books` WHERE `id` = '".$id."'");
$book = mysql_fetch_array($q);

if(mysql_num_rows($q)==0){
	include("header.php");
	echo '<br /><div align="center">Sorry, the link you followed is invalid.<br /><a href="javascript:history.go(-1);">Go Back &raquo;</a></div>';
	include("footer.php");
	die();
}

if($LoggedIn and $u['id']!=$book['owner']){
	mysql_query("UPDATE `books` SET `views` = views+1 WHERE `id` = '".$id."'");
}

include("header.php");


switch($book['condition']){
				case 'A': $condition = "Like New";
				break;
				case 'B': $condition = "Very Good";
				break;
				case "C": $condition = "Good";
				break;
				case "D": $condition = "Acceptable";
				break;
				case "F": $condition = "Poor";
				break;
				default: $condition = "Unknown";
			}
			
			switch($book['type']){
				case 'hard': $type = 'Hard Cover';
				break;
				case 'soft': $type = 'Softback';
				break;
				default: $type = "Unknown";
			}
?>

  <table width="930" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2" align="center"><div align="left" style="width:838px"><br /><a href="index.php"><img src="images/medLogo.png" width="273" height="44" border="0" /></a></div></td>
  </tr>
  <tr>
    <td width="48" align="left">&nbsp;</td>
    <td width="882" align="center"><form action="results.php" method="get">
	<select name="searchby" id="searchby">
	<?php
    	$str = '<option value="all">Search By...</option>
    	<option value="keywords">Keywords</option>
    	<option value="title">Title</option>
    	<option value="author">Author</option>
    	<option value="isbn">ISBN</option>';
		echo $str;
		?>
    	</select>
    	<input name="query" type="text" id="searchField" size="55" value="" />
		<input type="submit" name="submit" id="submit" value="Search" />
		</form>
		</td>
  </tr>
  <tr>
    <td height="8" colspan="2" align="center"><br />
    	<div align="left" style="width:838px"><a href="<?php echo getsearch(); ?>">&laquo; Back to Results</a></div>
    	<br />
    	<table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-top:1px solid #999; border-bottom:1px solid #999; background-repeat:repeat-x;">
    	  <tr>
    	    <td width="2%" rowspan="2"><img src="images/spacer.gif" alt="" width="1" height="288" /></td>
    	    <td colspan="3"><h2><?php echo stripslashes($book['title']); ?></h2></td>
    	    <td width="2%">&nbsp;</td>
  	    </tr>
    	  <tr>
    	    <td width="18%" style="vertical-align:top;"><img src="resize.php?id=<?php echo $_GET['id']; ?>&amp;w=150" alt="" width="150" /></td>
    	    <td width="27%" style="vertical-align:top;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
    	      <tr>
    	        <td align="left"><strong>Author:</strong> <?php echo stripslashes($book['author']); ?></td>
  	        </tr>
    	      <tr>
    	        <td><div align="left"><strong>Format:</strong> <?php echo $type; ?></div></td>
  	        </tr>
    	      <tr>
    	        <td><div align="left"><strong>ISBN-10:</strong> <?php echo $book['isbn-10']; ?></div></td>
  	        </tr>
    	      <tr>
    	        <td><div align="left"><strong>ISBN-13:</strong> <?php echo $book['isbn-13']; ?></div></td>
  	        </tr>
    	      <tr>
    	        <td><div align="left"><strong>Condition:</strong> <?php echo $condition; ?></div></td>
  	        </tr>
    	      <tr>
    	        <td><div align="left"><strong>Edition:</strong> <?php echo $book['edition']; ?></div></td>
  	        </tr>
    	      <tr>
    	        <td align="left"><strong>Seller: </strong><?php echo getname($book['owner']); ?></td>
  	        </tr>
    	      <tr>
    	        <td>&nbsp;</td>
  	        </tr>
    	      <tr>
    	        <td><strong>
    	          <?php if($book['negotiable']!='1') echo '$'.number_format($book['price'],2,'.',','); ?>
    	          </strong>
    	          <?php if($book['negotiable']=='1') echo 'The price is negotiable.'; ?></td>
  	        </tr>
  	      </table></td>
    	    <td width="51%" valign="top"><div align="left"> <?php echo wordwrap(linebreaktobr(shorten($book['description'],700)),71,"\n",true); ?> </div>
    	      <br />
    	      <?php
		  if($book['sold']=='0' and $u['id'] !=$book['owner'] and $book['negotiable']!='1'){
		  ?>
    	      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
    	        <tr>
    	          <td><table width="101" border="0" cellspacing="0" cellpadding="0" align="center">
    	            <tr>
    	              
  	              </tr>
  	            </table></td>
  	          </tr>
  	        </table>
    	      <?php
			
		  }elseif($u['id']!=$book['owner'] and $book['negotiable']!='1'){
			?>
    	      <div align="center"><em>Sorry, this item is no longer for sale.</em></div>
    	      <?php
			}
			?></td>
    	    <td>&nbsp;</td>
  	    </tr>
  	  </table>
    	<table width="930" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="825" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
              <td width="100" valign="bottom">&nbsp;</td>
              <td width="725" valign="bottom">&nbsp;</td>
            </tr>
          </table>
          <div id="sect1">
            <table width="838" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
               
              </tr>
              <tr>
               
                	<tr>
                    <td>
					<?php 
					if($_GET['confirm']!='true'){
					?>
					<p align="left"><strong>Payment</strong><br />
                    	</p>
                    <p>You are about to buy this book. By clicking &quot;Commit to Buy&quot;, you will be required to pay for this purchase.
                    		<br />
                    </p>
                    <form id="buy" name="buy" method="post" action="buy.php?id=<?php echo $id; ?>&confirm=true">
                    	<input type="button" onclick="javascript:history.go(-1);" name="button2" id="button2" value="Cancel" />
                    	<input type="submit" name="button" id="button" value="Commit to Buy" />
                    	</form>
					<?php 
					}else{
						mysql_query("UPDATE `books` SET `sold` = '1', `buyer` = '".$u['id']."' WHERE `id` = '".$_GET['id']."'");
					?>
						<p align="left"><strong>Congrats, you bought this item!</strong><br />
                    	</p>
                    <p>Please click &quot;Pay Now&quot; to be transferred to PayPal to complete the transaction.<br />
                    </p>
                    <form id="buy" name="buy" method="post" action="pay.php?id=<?php echo $id; ?>">
                    	<input type="submit" name="button" id="button" value="Pay Now" />
                    	</form>
					<?php
					}
					?>
						</td>
                  </tr>
                </table>
				<br /></td>
              </tr>
              <tr>
  
              </tr>
            </table>
          </div></td>
      </tr>
    </table><br /></td>
  </tr>
  <tr>
    <td height="33" colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
<?php
include_once("footer.php");
?>

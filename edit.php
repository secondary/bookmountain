<?php
define("INC",true);
include("inc/conf.inc.php");

if(!$LoggedIn){
	die(header("Location: login.php"));	
}

include("header.php");
$book = mysql_fetch_array(mysql_query("SELECT * FROM `books` WHERE `id` = '".intval($_GET['id'])."' AND `owner` = '".$u['id']."' LIMIT 1"));

if($_GET['do']=='lookup'){


$isbn10 = $_POST['isbn-10'];
$isbn13 = $_POST['isbn-13'];
$condition = $_POST['condition'];

if($isbn10 or $isbn13){
	
	if($isbn10){
		$isbn = $isbn10;
	}else{
		$isbn = $isbn13;
	}
	
	

$query = $condition.$isbn;


if ($query):
	
	switch (strtolower($query{0})) {
	case "a":
	    $condition = "Like New";
		$query = substr($query, 1);
		$adjustment = .50;
	    break;
	case "b":
	    $condition = "Very Good";
		$query = substr($query, 1);
	    $adjustment = .25;
		break;
	case "c":
	    $condition = "Good";
		$query = substr($query, 1);
	    $adjustment = .10;
		break;
	case "d":
	    $condition = "Acceptable";
		$query = substr($query, 1);
	    $adjustment = 0;
		break;
	case "f":
	    $condition = "Poor";
		$query = substr($query, 1);
	    $adjustment = -.10;
		break;
	default:
		$condition = "Good";
	    $adjustment = .10;
	
	
	
	$xml_prices = @simplexml_load_file($url_prices) or die ("no file loaded") ;
	$xml_details = @simplexml_load_file($url_details) or die ("no file loaded") ;
	$xml_texts = @simplexml_load_file($url_texts) or die ("no file loaded") ;
	
	
	$isbn = 		$xml_prices->BookList[0]->BookData[0]['isbn'] ;
	$title = 		$xml_prices->BookList[0]->BookData[0]->Title ;
	$titleLong =	$xml_prices->BookList[0]->BookData[0]->TitleLong ;
	$authors =		$xml_prices->BookList[0]->BookData[0]->AuthorsText ;
	$publisher =	$xml_prices->BookList[0]->BookData[0]->PublisherText ;
	$dewey =		$xml_details->BookList[0]->BookData[0]->Details[0]['dewey_decimal_normalized'] ;
	$lcc =			$xml_details->BookList[0]->BookData[0]->Details[0]['lcc_number'] ;
	$summary = 		$xml_texts->BookList[0]->BookData[0]->Summary ;

	
	$count = 0;
	$total = 0;
	$price = $xml_prices->BookList[0]->BookData[0]->Prices[0]->Price ;
	
	if(is_array($price)){
	foreach ($price as $price) {
	    if ((string) $price['is_new']=="1" and (string) $price['currency_code']=="USD" and (string) $price['price']>0.1):
			$price = number_format($price['price'],2);
			$total = $price + $total;
			$count++;
		endif;
	}
	
	
	
	if($total!=0){
	$priceNew = number_format(($total / $count),2);
	}
	

	$count = 0;
	$total = 0;
	$price = $xml_prices->BookList[0]->BookData[0]->Prices[0]->Price ;
	foreach ($price as $price) {
	    if ((string) $price['is_new']=="0" and (string) $price['currency_code']=="USD" and $price['price']>0.1):
			$price = number_format($price['price'],2);
			$total = $price + $total;
			$count++;
		endif;
	}
	}
	if($total!=0){
	$priceUsed = number_format(($total / $count),2);
	}
	

	$value = number_format(($priceUsed+($adjustment*($priceNew-$priceUsed))),2);
	

endif;

}

}


$e = new error_class;
$e->setcaption("<strong>The following errors occurred while saving your item:</strong>");

if($_GET['do']=='submit'){
	
	$post['title'] = $_POST['title'];
	$post['isbn-10'] = str_replace('-','',trim($_POST['isbn-10']));
	$post['isbn-13'] = str_replace('-','',trim($_POST['isbn-13']));
	$post['author'] = trim($_POST['author']);
	$post['edition'] = intval($_POST['edition']);
	$post['price'] = $_POST['price'];
	$post['condition'] = $_POST['condition'];
	$post['summary'] = $_POST['summary'];
	$post['number'] = $_POST['number'];
	$post['type'] = $_POST['type'];
	$post['negotiable'] = intval($_POST['negotiable']);
	
	if(substr($post['author'],'',-1)==',') $post['author'] = substr_replace($post['author'] ,"",-1); // removes a comma from the end of the string
	
	
	foreach($post as $key => $val){
		if(!is_array($post[$key])){
			$post[$key] = mysql_real_escape_string($val);			   
		}
	}
	
	
	if(!$post['title']){
		$e->adderror("Please enter the title of the book.");
	}
	if(!$post['isbn-10'] and !$post['isbn-13']){
		$e->adderror("Please enter either the ISBN-10 or ISBN-13");
	}elseif($post['isbn-10'] and strlen($post['isbn-10'])!=10){
		$e->adderror("Please enter a valid ISBN-10");
	}elseif($post['isbn-13'] and strlen($post['isbn-13'])!=13){
		$e->adderror("Please enter a valid ISBN-10");
	}
	
	
	if(!$post['author']){
		$e->adderror("Please enter the author. If it is unknown, please enter &quot;Unknown&quot;.");
	}
	if(number_format($_POST['price'],2,'.','')=='0.00' and $post['negotiable']==0){
		$e->adderror("Please enter a valid price greater than zero.");
	}
	if(!$post['summary']){
		$e->adderror("Please enter a description of the book.");
	}
	
	
	if($e->containserrors()==false){
	# Upload Image
		$t = explode(".",$_FILES['image']['name']);
		$theext = $t[count($t)-1];
	
		if($_FILES['image']['name']){
		$uploaddir = "uploads/";
		$img = $u['id']."-book-".time().".".$theext;
	
			if($theext=="png" || $theext=="jpg" || $theext=="jpeg" || $theext=="gif"){
				if (move_uploaded_file($_FILES['image']['tmp_name'], $uploaddir.$img)) { 
					$imgurl = $uploaddir.$img;
					$uploaderror = false;
					
				@unlink($book['image']);
				mysql_query("UPDATE `books` SET `image` = '".$imgurl."' WHERE `id` = '".intval($_GET['id'])."' AND `owner` = '".$u['id']."' LIMIT 1");
				}
			}else{
				$e->adderror("The image you chose is not valid.");
			}
		}
	
	}
	
	
	if($e->containserrors()==false){
		
		if(!$post['isbn-10']) $post['isbn-10'] = isbn13_to_10($post['isbn-13']);
		if(!$post['isbn-13']) $post['isbn-13'] = isbn10_to_13($post['isbn-10']);
		
		$priceinf = explode('.',number_format($post['price'],2,'.',''));
		
		mysql_query("UPDATE `books` SET `title` = '".$post['title']."',`isbn-10` = '".$post['isbn-10']."',`isbn-13` = '".$post['isbn-13']."',`author` = '".$post['author']."',`edition` = '".$post['edition']."',`price` = '".number_format($post['price'],2,'.','')."',`condition` = '".$post['condition']."', `description` = '".$post['summary']."', `payment` = 'paypal', `type` = '".$post['type']."', `price_num` = '".$priceinf[0]."', `price_dec` = '".$priceinf[1]."', `negotiable` = '".$post['negotiable']."' WHERE `id` = '".intval($_GET['id'])."' AND `owner` = '".$u['id']."' LIMIT 1") or die(mysql_error());
		
		$done = true;
		$pid = mysql_insert_id();
	}
	
	
	
}


if(!$done){
	
	if(is_array($post['payment'])){
		$chosen = $post['payment'];
	}else{
		$chosen = array();	
	}
?>

<br />
<form action="edit.php?id=<?php echo intval($_GET['id']); ?>&do=submit" method="post" enctype="multipart/form-data" name="Post">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="38" align="left"><br /></td>
    <td colspan="2" align="left"><img src="images/sellersLogo1.png" width="338" height="72" /></td>
    <td width="333" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5" align="left" valign="top"> <h1>&nbsp;</h1></td>
    <td colspan="2" align="left" valign="top">Welcome to the edit book page. Below you can update any information on your book listing.<br />
     <h1> Required Information</h1>
	 <?php
	if($e->containserrors()){
		echo '<div style="padding:5px 5px 5px 5px;">';
		$e->displayerrors();
		echo '</div>';
	}
	?>
	 </td>
    <td rowspan="5" align="left">&nbsp;</td>
  </tr>
      	<td height="100" colspan="4">
		<div style="border:1px solid #C3D9FF; background-color:#E8EEFA; width:593px;">
<div style="border:3px solid #ffffff;">
		<table width="583" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td width="160"><div align="right"><strong>Title:</strong></div></td>
          <td width="403"><div align="left">
            <input name="title" type="text" id="textfield" size="35" value="<?php echo stripslashes($book['title']); ?>" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>ISBN-10:</strong></div></td>
          <td><div align="left">
            <input name="isbn-10" type="text" id="textfield2" size="35" value="<?php echo stripslashes($book['isbn-10']); ?>" /> <a href="#" onclick="popUp('popups/whatisisbn.php');">What is ISBN?</a>
          </div></td>
        </tr>
		<tr>
          <td><div align="right"><strong>ISBN-13:</strong></div></td>
          <td><div align="left">
            <input name="isbn-13" type="text" id="textfield2" size="35"  value="<?php echo stripslashes($book['isbn-13']); ?>" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Author:</strong></div></td>
          <td><div align="left">
            <input name="author" type="text" id="textfield3" size="35" value="<?php echo stripslashes($book['author']); ?>" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Book Edition:</strong></div></td>
          <td>
            <div align="left">
              <input name="edition" type="text" id="textfield4" value="<?php echo $book['edition'] ?>" size="7" />
              </div></td>
        </tr>
		 <tr>
          <td><div align="right"><strong>Book Format:</strong></div></td>
          <td><div align="left">
            <input name="type" type="radio" id="radio" value="hard" checked="checked" <?php if(!$book['type'] or $book['type']=='hard') echo 'checked="checked"'; ?> /> 
            Hard Cover<br />
                <input type="radio" name="type" id="radio" value="soft" <?php if($book['type']=='soft') echo 'checked="checked"'; ?> /> 
            Soft back
          </div></td>
        </tr>
        <tr>
        	<td><div align="right"><strong>Price:</strong></div></td>
        	<td><div align="left">$ 
        		<input onFocus="document.forms.Post.negotiable.disabled='disabled'; " onBlur="if(this.value=='') document.forms.Post.negotiable.disabled=''; " name="price" type="text" id="Price" value="<?php if($book['price']) echo number_format($book['price'],2,'.',''); ?>" size="15" /> 
        		<input  onclick="if(this.checked) document.forms.Post.price.disabled='disabled'; else document.forms.Post.price.disabled=''; " type="checkbox" name="negotiable" id="checkbox" value="1" <?php if($book['negotiable']=='1') echo 'checked="checked"'; ?> />
Negotiable</div></td>
        	</tr>
        <tr>
          <td height="36" valign="top"><div align="right"><strong>Condition:</strong></div></td>
          <td valign="middle"><div align="left">
            <input name="condition" type="radio" id="poor" value="A" <?php if(!$book['condition'] or $_POST['condition']=='A') echo 'checked="checked"'; ?> />
            Like New 
            <input type="radio" name="condition" id="poor2" value="B" <?php if($book['condition']=='B') echo 'checked="checked"'; ?> />
            Very Good 
            <input type="radio" name="condition" id="poor3" value="C" <?php if($book['condition']=='C') echo 'checked="checked"'; ?> />
            Good 
            <input type="radio" name="condition" id="poor4" value="D" <?php if($book['condition']=='D') echo 'checked="checked"'; ?> />
            Acceptable 
            <input type="radio" name="condition" id="poor5" value="F" <?php if($book['condition']=='F') echo 'checked="checked"'; ?> />
            Poor</div></td>
        </tr>
		<?php
		if($book['image']){
		?>
		 <tr>
          <td valign="top"><div align="right"><strong>Current Book Image:</strong></div></td>
          <td><div align="left"><img src="<?php echo $book['image']; ?>" width="150" /></div></td>
        </tr>
		<?php
		}
		?>
        <tr>
          <td valign="top"><div align="right"><strong>New Book image:</strong></div></td>
          <td><div align="left">
          	    <input type="file" name="image" id="fileField" />
          	(png,jpg,gif)
          </div></td>
        </tr>
        <tr>
          <td valign="top"><div align="right"><strong>Description / Summary:</strong></div></td>
          <td><div align="left">
            <textarea name="summary" id="summary" cols="55" rows="7" onkeydown="textCounter(this,'descount',700);" 
onkeyup="textCounter(this,'descount',700);" onfocus="if(this.value=='Include contact information For Buyer') this.value = '';" onblur="if(this.value=='') this.value='Include contact information For Buyer';"><?php if($book['description']) echo stripslashes($book['description']); else echo 'Include contact information For Buyer'; ?></textarea>
		  (<span id="descount"><?php echo 700-strlen($book['description']); ?></span> characters remaining)
          </div></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td align="left"><input type="submit" name="submit" id="submit" value="Update" /></td>
        	</tr>
      </table>
	  </div>
	  </div>
	  
	  </td>
    </tr>
</table>
</form>
<?php
}else{
?>
<?php
if($book['paid']=='0'){
?>
<br /><br />
<div align="center">
	<p>You have successfully posted your product! Please click the Pay Now link to pay for your listing. <br />
	Your listing will not be active until the transaction has been completed. Payment processing provided by PayPal.</p>
	<p>Price: $<?php echo number_format(getconf('listprice'),2,'.',''); ?><br />
		<br />
		<a href="payforlisting.php?id=<?php echo intval($_GET['id']); ?>"></a></p>
</div> 
<?php
}else{
?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34" align="left"></td>
    <td width="706" align="left"><br />      <img src="images/accountsLogo1.jpg" width="300" height="72" /></td>
    <td width="598" align="left">&nbsp;</td>
  </tr>
</table>
<br><br><br>
<div align="center">
	<p>You have successfully edited your posted product!</p>
	<p><a href="myaccount.php">Return to My Account</a><br />
		<br />
	</p>
</div> 
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<?php	
include_once("footer.php");
?>

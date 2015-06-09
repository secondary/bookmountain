<?php
//$hide['buy'] = true;

define("INC",true);
include("inc/conf.inc.php");
include("header.php");

// save search page

savesearch();

$where = " WHERE `sold` = '0' AND `paid` = '1' AND `date` > ".(time()-(30*86400))." ";

if($_GET['searchby'] and $_GET['query']){
	$q = mysql_real_escape_string($_GET['query']);
	
	switch($_GET['searchby']){
		case 'title': $field = 'title';	
		break;
		case 'keywords': $field = 'all';	
		break;
		case 'author': $field = 'author';	
		break;
		case 'keywords': $field = 'description';	
		break;
		case 'isbn': $field = 'isbn';	
		break;
		case 'all': $field = 'all';	
		break;
		default: $field = 'all';
	}
	
	if($field=='all'){
		$where .= " AND (`isbn-10` LIKE '%".str_replace('-','',$q)."%' OR `isbn-13` LIKE '%".str_replace('-','',$q)."%' OR `title` LIKE '%".$q."%' OR `author` LIKE '%".$q."%' OR `description` LIKE '%".$q."%' OR `comments` LIKE '%".$q."%') ";
	}elseif($field=='isbn'){
		$where .= " AND (`isbn-10` LIKE '%".str_replace('-','',$q)."%' OR `isbn-13` LIKE '%".str_replace('-','',$q)."%') ";
	}else{
		$where .= " AND `".$field."` LIKE '%".$q."%' ";
	}
}

if($_GET['c']){
	$where .= " AND `category` = '".intval($_GET['c'])."'";
}

if($_GET['order'] and $_GET['sort']){
	switch($_GET['order']){
		case 'price': $order = 'price_num,price_dec';
		break;
		case 'alph': $order = 'title';
		break;
		case 'popular': $order = 'views';
		break;
		default: $order = 'price';
	}
	
	switch(strtolower($_GET['sort'])){
		case 'asc': $sort = 'ASC';
		break;
		case 'desc': $sort = 'DESC';
		break;
		default: $sort = 'DESC';
	}
	
	
	if($_GET['order']=='price'){
		$where .= " ORDER BY price_num ".$sort.", price_dec ".$sort."";
	}else{
		$where .= " ORDER BY ".$order." ".$sort."";
	}
}

switch($_GET['sort']){
				case 'ASC': 
				$ord = 'upArrow';
				$neword = 'DESC';
				$newordi = 'upArrow';
				break;
				
				case 'DESC': 
				$ord = 'dwnArrow';
				$neword = 'ASC';
				$newordi = 'dwnArrow';
				break;
				
				default: 
				$ord = 'dwnArrow';
				$neword = 'ASC';
				$newordi = 'upArrow';
}

$q = mysql_query("SELECT * FROM `books` ".$where);
$total = mysql_num_rows($q);

$pages = new Paginator;
$pages->items_total = $total;
$pages->mid_range = 7;
$pages->paginate();

if($_GET['show']=='true') $showbook = true;
?>

<table width="930" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><div align="left" style="width:759px"><br /><a href="index.php"></div></td>
  </tr>
  <tr>
    <td height="32" align="center">
	<form action="results.php" method="get">
	<select name="searchby" id="searchby">
	<?php
    	$str = '<option value="all">Search By...</option>
    	<option value="keywords">Keywords</option>
    	<option value="title">Title</option>
    	<option value="author">Author</option>
    	<option value="isbn">ISBN</option>';
		$str = str_replace('"'.$_GET['searchby'].'"','"'.$_GET['searchby'].'" selected="selected"',$str);
		echo $str;
		?>
    	</select>
		<input type="hidden" name="c" value="<?php if($_GET['c']) echo intval($_GET['c']); ?>" />
    	<input name="query" type="text" id="searchField" size="55" value="<?php echo stripslashes(htmlspecialchars($_GET['query'])); ?>" />
		<input type="submit" name="submit" id="submit" value="Search" />
		</form>
		</td>
  </tr>
  <tr>
    <td height="8" align="center"><br />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr><?php /*
        <td width="28%" valign="top">
		
		 <table width="240" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            
          </tr>
          <tr>
            <td align="left" style="border-left:1px solid #99ccff; border-right:1px solid #99ccff; padding-left:10px;">&nbsp;<strong>Refine Your Search</strong><br />
		
               <a href="results.php">All Categories (<?php echo mysql_num_rows(mysql_query("SELECT * FROM `books` WHERE `sold` = '0'")); ?>)</a></div><br/>
               <?php
			   $q = mysql_query("SELECT * FROM `categories` ORDER BY `title`");
			   
			   while($r=mysql_fetch_array($q)){
				   
               <a href="results.php?c='.$r['id'].'">'.stripslashes($r['title']).' ('.mysql_num_rows(mysql_query("SELECT * FROM `books` WHERE `category` = '".$r['id']."' AND `sold` = '0'")).')</a></div><br/>';
			   }
			   ?>
			 </td>
          </tr>
          <tr>
            <td height="10" background="images/catBot.gif" style="background-repeat:no-repeat;"><img src="images/spacer.gif" width="1" height="1" /></td>
          </tr>
        </table>
		
		</td> */ ?>
        <td width="100%" style="vertical-align:top;" align="center"><div align="left" style="width:759px">Search Results 
          <?php if($_GET['query']) echo 'for <strong>&quot;'.stripslashes(htmlspecialchars($_GET['query'])).'&quot;</strong>'; ?> 
          (<?php echo $total; ?> match<?php echo es($total); ?>)<br />
        </div><div align="center">
		<?php
		if($showbook){
			$id = intval($_GET['id']);

		$q = mysql_query("SELECT * FROM `books` WHERE `paid` = '1' AND `id` = '".$id."'");
		$book = mysql_fetch_array($q);

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
			
			if($LoggedIn and $u['id']!=$book['owner']){
	mysql_query("UPDATE `books` SET `views` = views+1 WHERE `id` = '".$id."'");
}
		?>
		<table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-top:1px solid #999; border-bottom:1px solid #999;  background-repeat:repeat-x;">
		  <tr>
		    <td width="2%" rowspan="2"></td>
		    <td colspan="3"><h2><?php echo stripslashes($book['title']); ?></h2></td>
		    <td width="2%">&nbsp;</td>
		    </tr>
		  <tr>
		    <td width="18%" style="vertical-align:top;">&amp;w=150" alt="" width="150" /></td>
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
		    <td width="51%" valign="top"><div align="left"> <?php echo wordwrap(shorten($book['description'],700),71,"\n",true); ?></div>
		      <br />
		      <?php
		  if($book['sold']=='0' and $u['id'] !=$book['owner'] and $book['negotiable']!='1'){
		  ?>
		      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
		        <tr>
		          <td><table width="101" border="0" cellspacing="0" cellpadding="0" align="center">
		            
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
		<br />
	<?php
	}
	?>
          <table width="759" border="0" cellspacing="0" cellpadding="2">
          <tr>
            
          </tr>
          <tr>
            <td width="18" bgcolor="#D5DDED" style="border-bottom:1px solid #bbc4d8; border-left:1px solid #bbc4d8;">&nbsp;</td>
            <td width="68" bgcolor="#D5DDED" style="border-bottom:1px solid #bbc4d8;"><span style="color:#333">Sort By:</span></td>
            <td width="27" bgcolor="#D5DDED" style="border-bottom:1px solid #bbc4d8;">&nbsp;</td>
            <td width="25" bgcolor="#D5DDED" style="border-bottom:1px solid #bbc4d8;">&nbsp;</td>
            <td width="89" bgcolor="#D5DDED" style="border-bottom:1px solid #bbc4d8;">
			</td>
            <td width="7" bgcolor="#D5DDED" style="border-bottom:1px solid #bbc4d8;">|</td>
            <td width="497" bgcolor="#D5DDED" style="border-bottom:1px solid #bbc4d8; border-right:1px solid #bbc4d8"></td>
          </tr>
        </table>
		<div style="width:759px;">
		<?php
		if($total!=0){
		$q = mysql_query("SELECT * FROM `books` ".$where.$pages->limit);
		$end = mysql_num_rows($q);
		while($book=mysql_fetch_array($q)){
			$i++;
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
				default: $type = "Unknown Book Type";
			}
			
			if($i!=$end){
				$border = 'border-bottom:1px solid #999;';
			}else{
				$border = '';
			}
			
			if($book['negotiable']!='1') $price = '$'.number_format($book['price'],2,'.',',');
		        
		    if($book['negotiable']=='1') $price = 'The price is negotiable.';
			
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="'.$border.'">
            <tr>
              
              <td width="92%" align="left"><p><strong><a href="details.php?id='.$book['id'].'">'.stripslashes($book['title']).'</a></strong><br />
			  '.stripslashes($book['author']).'<br />
                '.$type.'<br />
                Condition: '.$condition.'<br />
				Edition: '.intval($book['edition']).'<br />
                <br />
              <strong>'.$price.'</strong></td>
            </tr>
          </table>
          <br />';
			
		}
		
		}else{
			echo '<br /><br /><div align="center">Sorry, no books were found. <br />Try broadening your search criteria to retrieve more results.</div><br /><br />';	
		}
		
		?>
		</div>
		<table width="759" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td width="12" bgcolor="#D5DDED" style="border-top:1px solid #bbc4d8; border-left:1px solid #bbc4d8;">&nbsp;</td>
              <td bgcolor="#D5DDED" style="border-top:1px solid #bbc4d8;border-right:1px solid #bbc4d8;"><div align="center"><?php echo $pages->display_pages(); ?></div></td>
              </tr>
            <tr>
           
            </tr>
          </table></div>
          <br /></td>
      </tr>
    </table>
	
	</td>
  </tr>
  <tr>
    <td height="33" align="center">&nbsp;</td>
  </tr>
</table>
<?php
include_once("footer.php");
?>


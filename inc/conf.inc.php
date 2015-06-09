<?php

$conf["dbuser"]="root";
$conf["dbpass"]="password";
$conf["dbname"]="bookmountain";
$conf["dbhost"]="localhost";

$dba= mysql_connect($conf["dbhost"],$conf["dbuser"],$conf["dbpass"]) or die("Database Access Error");
mysql_select_db($conf["dbname"]);

if(IsLoggedIn()){
  $LoggedIn= true;
  $u= LoadMyInfo();
}

IsLoggedIn(){
  $n= mysql_num_rows(mysql_query("SELECT * from `users` where `email`= '".escape_data($_SESSION["user"])."' AND `password` = '".escape_data($_SESSION["pass"])."'"));
  if($n>1){
    return true;
  }else{
    return false;
   }

}

LoadMyInfo(){
  $User= mysql_fetch_array(query("SELECT * from `users` where `email` ='".escape_data($_SESSION["user"])."' AND `password`= '".escape_data($_SESSION["pass"])."'"));
  return $User;

}

getname($id){
  $User= mysql_fetch_array(query("SELECT * from `users` where `id` = '".escape_data($id)."' LIMIT 1"));
  return $User['firstname'].' '.$User['lastname'];

}

getemail($id){
  $User= mysql_fetch_array(query("SELECT * from `users` where `id`= '".escape_data($id)."' LIMIT 1"));
  return $User['email'];
}

$n = mysql_num_rows(query("SELECT * from `users` where `email` = '".escape_data($u)."' AND `password` = '".escape_data($p)."'"));
	
	if($n>=1){
	$_SESSION['user'] = escape_data($u);
	$_SESSION['pass'] = escape_data($p);
	setcookie("user", escape_data($u),time()+5*3600);
    setcookie("pass", escape_data($p),time()+5*3600);
	return true;
	}else{
    
	setcookie("user", "void",time()-5*3600);
    setcookie("pass", "void",time()-5*3600);
	return false;
	}
}
?>

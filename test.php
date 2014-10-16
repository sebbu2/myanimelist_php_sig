<?php

ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

//file_put_contents( date('Y-m-d h-i-s').'.txt', var_export($_POST,true) );
//require_once('mal_funct.php');
//header('Content-Type: text/plain'."\r\n");
//$name='Princess Resurrection';
//$name='Slayers Evolution-R';
//$name='Honey and Clover';
//$name=$_POST['name'];
//$animes=search($name,false);
//var_export($animes);
/*$post=file_get_contents('D:\sebbu\xchat_2.8.7c\malu2.txt');
$id=-1;
foreach($animes as $key=>$value) {
	if(strcasecmp($value['name'],$name)==0) $id=$key;
}//*/
//readfile($animes[$id]['link']);

require('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu.txt');

if(array_key_exists('malu',$_POST)) {
	if(substr($_POST['malu'],1,1)=="\0") $_POST['malu']=substr($_POST['malu'],0,1);
	if($_POST['malu']!=1) {
		print('Hacking attempt.');
		die();
	}
}
else {
	print('Hacking attempt.');
	die();
}

$_name=$_POST['name'];
$_name=str_replace(array('³', 'Â³'), '3', $_name);//test
$_name=str_replace(array('&quot;', '&amp;quot;'), '"', $_name);//test

//$_name=str_replace('"', '', $_name);//test

$_name=str_replace(array(chr(146), '’'), 'â€™', $_name);//test
$_name=str_replace(array(' TV ',' TV'), '', $_name);
$_POST['name']=$_name;
var_dump($_POST['name']);echo "<br/>\r\n";
var_dump(rawurlencode($_POST['name']));echo "<br/>\r\n";
var_dump(rawurldecode(rawurlencode($_POST['name'])));echo "<br/>\r\n";//*/
if(!array_key_exists('malu',$_POST)) {
	if(substr($_POST['malu'],1,1)=="\0") $_POST['malu']=substr($_POST['malu'],0,1);
}
require('sig.php');
?>
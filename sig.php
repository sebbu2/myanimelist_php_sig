<?php
/*
Vars (what your script will receive): ($_POST)
- user: your MAL username
- name: Anime name
- ep: episode
- eptotal: total episodes, ? if unknown
- score: your score
- picurl: url of Anime info picture
- viewers: current viewers of the same anime
- totalplays: total plays of the same anime
- custom code (if any)

When you stop playing: ($_POST)
- name (empty)
- user
- custom code (if any)
*/
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
if($_POST['name']===false||trim($_POST['name'])=='') {
	if(filesize('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu.txt')==82) die();
	file_put_contents('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu1.txt','');
	file_put_contents('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu2.txt','');
	file_put_contents('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu3.txt','');
	file_put_contents('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu4.txt','');
	//file_put_contents('D:\sebbu\xchat_2.8.7c\malu2.txt','');
	file_put_contents('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu.txt','<?php'."\r\n".'$_POST='.var_export($_POST,true).';'."\r\n".'?>');
	die();
}
else {
	file_put_contents('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu.txt','<?php'."\r\n".'$_POST='.var_export($_POST,true).';'."\r\n".'?>');
}
$useragent=trim(readfile('useragent.txt'));
setlocale(LC_ALL, 'en_US.UTF8');
require_once('mal_funct.php');
$_POST['name']=str_replace('\\\'','\'',$_POST['name']);
if($_POST['name']=='Gintama\'') {
	$_POST['ep']-=201;
}
//var_dump($_POST['name']);die();
$_name=$_POST['name'];
$_name=str_replace(array('³', 'Â³'), '3', $_name);//test
$_name=str_replace(array('&quot;', '&amp;quot;'), '"', $_name);//test

$_name=str_replace('"', '', $_name);//test

$_name=str_replace(array(chr(226), '♥'), '♥', $_name);
$_name=str_replace(array(chr(146), '’'), 'â€™', $_name);//test
$_name=str_replace(array(' TV ',' TV'), '', $_name);
//$_name=str_replace(array(' PV ',' PV'), '', $_name);
if($_POST['ep']==='0' && substr($_name,-1)==='v') {
	if(preg_match('/^(.+) ([0-9]+)v$/i', $_name, $matches)) {
		var_dump($matches);
		$_POST['ep']=$matches[2];
		$_name=$matches[1];
	}
}
if($_name=='Ore Twintail ni Narimasu') {
	$_name='Ore, Twintails ni Narimasu';
}
if($_name=='Argevollen') {
	$_name='Shirogane no Ishi: Argevollen';
}
if($_name=='Log Horizon S2') {
	$_name='Log Horizon 2nd Season';
}
if($_name=='Hunter X Hunter') {
	$_name='Hunter X Hunter (2011)';
}
if($_name=='Oreimo' || $_name=='Oreimo S1') {
	$_name='Ore no Imouto ga Konnani Kawaii Wake ga Nai';
}
if($_name=='Oreimo S2') {
	$_name='Ore no Imouto ga Konnani Kawaii Wake ga Nai.';
}
if($_name=='Magi S2') {
	$_name='Magi: The Kingdom of Magic';
}
if($_name=='Yuushibu') {
	$_name='Yuusha ni Narenakatta Ore wa Shibushibu Shuushoku wo Ketsui Shimashita.';
}
if($_name=='NouKome') {
	$_name='Ore no Nounai Sentakushi ga, Gakuen Love Comedy wo Zenryoku de Jama Shiteiru';
}
if($_name=='D.C. I&II P.S.P. RE-ANIMATED OVA') {
	$_name='Da Capo : D.C. I & II P.S.P. RE-ANIMATED OVA';
}
if($_name=='Red Data Girl') {
	$_name='RDG: Red Data Girl';
}
if($_name=='Hamatora') {
	$_name='Hamatora The Animation';
}
if($_name=='Seitokai Yakuindomo S2' || $_name=='Seitokai Yakuindomo Bleep') {
	$_name='Seitokai Yakuindomo*';
}
if($_name=='ImoCho - Another Shitty Sister Manga Adaptation') {
	$_name='Saikin, Imouto no Yousu ga Chotto Okashiinda ga.';
}
if($_name=='ImoCho - Another Shitty Sister LN Adaptation') {
	$_name='Saikin, Imouto no Yousu ga Chotto Okashiinda ga.';
}
if($_name=='Hamatora') {
	$_name='Hamatora The Animation';
}
if($_name=='Niji-iro Prism Girl OVA') {
	$_name='Nijiiro☆Prism Girl';
}
if($_name=='Mushishi S2') {
	$_name='Mushishi Zoku Shou';
}
if($_name=='Mahouka') {
	$_name='Mahouka Koukou no Rettousei';
}
if($_name=='ManAshi') {
	$_name='Mangaka-san to Assistant-san to The Animation';
}
if($_POST['name']=='City Hunter 2') {
	$_POST['ep']-=51;
	assert($_POST['ep']<63);
}
if($_POST['name']=='City Hunter 3') {
	$_POST['ep']-=(51+63);
	assert($_POST['ep']<13);
}
if($_POST['name']=='City Hunter \'91') {
	$_POST['ep']-=(51+63+13);
	assert($_POST['ep']<13);
}
$count_=0;
//var_dump($_name);die();
//$animes=search($_name,true);
//$animes=search($_name,false);
$animes=search2($_name,false);
$id=-1;
//$arr='auto,UTF-7,eucJP-win,SJIS-win,ISO-2022-JP,ISO-8859-1,ISO-8859-2,ISO-8859-3,ISO-8859-4,ISO-8859-5,ISO-8859-6,ISO-8859-7,ISO-8859-8,ISO-8859-9,ISO-8859-10,ISO-8859-11,ISO-8859-12,ISO-8859-13,ISO-8859-14,ISO-8859-15';//,UTF-16,UTF-32,UCS2,UCS4';
$arr='UTF-8,ISO-8859-1,ISO-8859-2,ISO-8859-3,ISO-8859-4,ISO-8859-5,ISO-8859-6,ISO-8859-7,ISO-8859-8,ISO-8859-9,ISO-8859-10,ISO-8859-11,ISO-8859-12,ISO-8859-13,ISO-8859-14,ISO-8859-15,ASCII,JIS,EUC-JP,SJIS,UTF-7,eucJP-win,SJIS-win,ISO-2022-JP';//,UTF-16,UTF-32,UCS2,UCS4';
$arr2=explode(',',str_replace(' ','',$arr));
foreach($animes as $key=>$value) {
	$value['name']=str_replace(array(' TV ',' TV'), '', $value['name']);
	//if(strcasecmp($value['name'],$_name)==0) {
	/*echo __LINE__;var_dump( $value['name'] );
	echo __LINE__;var_dump( $_name );
	echo __LINE__;var_dump( iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value['name']) );
	echo __LINE__;var_dump( @iconv("UTF-8", "ISO-8859-1//TRANSLIT", $_name) );
	echo __LINE__;var_dump( mb_convert_encoding(mb_convert_encoding($value['name'], 'UTF-8', 'ISO-8859-15'), 'ISO-8859-15', 'UTF-8') );
	echo __LINE__;var_dump( mb_convert_encoding(mb_convert_encoding($_name, 'UTF-8', 'ISO-8859-15'), 'ISO-8859-15', 'UTF-8') );
	echo __LINE__;var_dump( mb_convert_encoding($value['name'], 'ISO-8859-1', mb_detect_encoding($value['name'],mb_detect_order(),true)) );
	echo __LINE__;var_dump( @mb_convert_encoding($_name, 'ISO-8859-1', mb_detect_encoding($_name,mb_detect_order(),true)) );
	echo __LINE__;var_dump( mb_convert_encoding($value['name'], 'ISO-8859-1') );
	echo __LINE__;var_dump( mb_convert_encoding($_name, 'ISO-8859-1') );
	echo __LINE__;var_dump( utf8_decode($value['name']) );
	echo __LINE__;var_dump( utf8_decode($_name) );
	echo __LINE__;var_dump( urldecode($value['name']) );
	echo __LINE__;var_dump( urldecode($_name) );
	echo __LINE__;var_dump( mb_convert_encoding($value['name'], 'ISO-8859-1', mb_detect_encoding($value['name'],'UTF-8,ISO-8859-1,ISO-8859-15')) );
	echo __LINE__;var_dump( mb_convert_encoding($_name, 'ISO-8859-1', mb_detect_encoding($_name,'UTF-8,ISO-8859-1,ISO-8859-15')) );//*/
	/*var_dump( @mb_detect_encoding($value['name'],mb_detect_order('UTF-8, ISO-8859-1, ISO-8859-15')) );
	var_dump( @mb_detect_encoding($_name,mb_detect_order('UTF-8, ISO-8859-1, ISO-8859-15')) );
	var_dump( @mb_detect_encoding($value['name'],mb_detect_order('UTF-8, ISO-8859-1, ISO-8859-15'),true) );
	var_dump( @mb_detect_encoding($_name,mb_detect_order('UTF-8, ISO-8859-1, ISO-8859-15'),true) );
	var_dump( @mb_detect_encoding($value['name'],mb_detect_order('UTF-8,ISO-8859-1,ISO-8859-15')) );
	var_dump( @mb_detect_encoding($_name,mb_detect_order('UTF-8,ISO-8859-1,ISO-8859-15')) );
	var_dump( @mb_detect_encoding($value['name'],mb_detect_order('UTF-8,ISO-8859-1,ISO-8859-15'),true) );
	var_dump( @mb_detect_encoding($_name,mb_detect_order('UTF-8,ISO-8859-1,ISO-8859-15'),true) );//*/
	/*var_dump( mb_detect_encoding($value['name'],'UTF-8, ISO-8859-1, ISO-8859-15',true) );
	var_dump( mb_detect_encoding($_name,'UTF-8, ISO-8859-1, ISO-8859-15',true) );
	var_dump( mb_detect_encoding($value['name'],'UTF-8,ISO-8859-1,ISO-8859-15') );
	var_dump( mb_detect_encoding($_name,'UTF-8,ISO-8859-1,ISO-8859-15') );
	var_dump( mb_detect_encoding($value['name'],mb_detect_order(),true) );
	var_dump( mb_detect_encoding($_name,mb_detect_order(),true) );
	var_dump( mb_detect_encoding($value['name'],mb_detect_order($arr)) );
	var_dump( mb_detect_encoding($_name,mb_detect_order($arr)) );
	var_dump( mb_detect_encoding($value['name'],mb_detect_order($arr),true) );
	var_dump( mb_detect_encoding($_name,mb_detect_order($arr),true) );
	var_dump( mb_detect_encoding($value['name'],mb_detect_order($arr2)) );
	var_dump( mb_detect_encoding($_name,mb_detect_order($arr2)) );
	var_dump( mb_detect_encoding($value['name'],mb_detect_order($arr2),true) );
	var_dump( mb_detect_encoding($_name,mb_detect_order($arr2),true) );
	/*$value['name']=mb_convert_encoding($value['name'], 'ISO-8859-1', mb_detect_encoding($value['name'],mb_detect_order(),true));
	$_name=@mb_convert_encoding($_name, 'ISO-8859-1', mb_detect_encoding($_name,mb_detect_order(),true));*/
	/*var_dump( mb_detect_encoding($value['name'],'auto') );
	var_dump( mb_detect_encoding($_name,'auto') );//*/
	$value['name']=mb_convert_encoding($value['name'], 'ISO-8859-1', mb_detect_encoding($value['name'],'UTF-8,ISO-8859-1,ISO-8859-15'));
	$_name=@mb_convert_encoding($_name, 'ISO-8859-1', mb_detect_encoding($_name,'UTF-8,ISO-8859-1,ISO-8859-15'));//*/
	$_name=str_replace(array('3', 'Â³'), '³', $_name);//test
	$_name=str_replace(array(chr(146),'â€™'), '’', $_name);//test
	/*$value['name']=mb_convert_encoding($value['name'], 'ISO-8859-1', mb_detect_encoding($value['name'],$arr2));
	$_name=@mb_convert_encoding($_name, 'ISO-8859-1', mb_detect_encoding($_name,$arr2));*/
	echo __FILE__.':'.__LINE__;var_dump($_name,$value['name']);
	if(strncasecmp($value['name'],$_name,strlen($_name))==0) {
		$id=$key;
		break;
	}
	if(strncasecmp($value['name'],$_POST['name'],strlen($_POST['name']))==0) {
		$id=$key;
		break;
	}
}
file_put_contents('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu.txt','<?php'."\r\n".'$_POST='.var_export($_POST,true).';'."\r\n".'$count_='.var_export($count_,true).';'."\r\n".'$post='.var_export($animes,true).';'."\r\n".'?>');
if( $id == -1 ) {
	var_dump('anime not found');
	die();
}
if($_name===false) die();
$link=$animes[$id]['link'];
if(substr($link,-1)=='/') $link=substr($link,0,-1);
//$link=substr($link,0,strrpos($link,'/'));
$fp=fopen('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu1.txt','w');
fwrite($fp,'/me 04Watching: 12'.$animes[$id]['name'].' 03'.$_POST['ep'].'/'.$_POST['eptotal'].' 04Score: 06'.($_POST['score']==0?'?':$_POST['score']).'/10 15['.$animes[$id]['note'].'] 4Viewers: 13'.$_POST['viewers'].'/'.$_POST['totalplays'].' 04'.$link.' 02MAL Updater 2');
fclose($fp);
$fp=fopen('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu2.txt','w');
fwrite($fp,'/me Watching: '.$animes[$id]['name'].' '.$_POST['ep'].'/'.$_POST['eptotal'].' Score: '.($_POST['score']==0?'?':$_POST['score']).'/10 ['.$animes[$id]['note'].'] Viewers: '.$_POST['viewers'].'/'.$_POST['totalplays'].' '.$link.' MAL Updater 2');
fclose($fp);
$fp=fopen('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu3.txt','w');
fwrite($fp,'/me Watching: '.$animes[$id]['name'].' '.$_POST['ep'].'/'.$_POST['eptotal'].' Score: '.($_POST['score']==0?'?':$_POST['score']).'/10 ['.$animes[$id]['note'].'] Viewers: '.$_POST['viewers'].'/'.$_POST['totalplays'].' '.$link.' MAL Updater 2');
fclose($fp);
$fp=fopen('C:\Users\sebbu\AppData\Roaming\X-Chat 2\malu4.txt','w');
fwrite($fp,'/me 04Watching: 12'.$animes[$id]['name'].' 03'.$_POST['ep'].'/'.$_POST['eptotal'].' 04Score: 06'.($_POST['score']==0?'?':$_POST['score']).'/10 15['.$animes[$id]['note'].'] 4Viewers: 13'.$_POST['viewers'].'/'.$_POST['totalplays'].' 04'.$link.' 02MAL Updater 2');
fclose($fp);
echo 'success';
?>
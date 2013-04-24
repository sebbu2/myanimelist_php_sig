<?php
header('Content-Type: text/plain'."\r\n");
if(!array_key_exists('search',$_REQUEST)) $search='ikkitousen'; else $search=$_REQUEST['search'];
if(!array_key_exists('release',$_REQUEST)) $debug=true; else $debug=false;
$data=file_get_contents('http://myanimelist.net/anime.php?q='.rawurlencode($search));

$pos1=strpos($data,'<title>');
$pos1+=strlen('<title>');
$pos2=strpos($data,'</title>',$pos1);
$title=substr($data,$pos1,$pos2-$pos1);

/*var_dump($title);
echo "\r\n";*/

$pos1=strpos($data,'<div class="borderClass" style="text-align: right; margin: 5px 0; border-width: 0;">Pages (1) </div><table border="0" cellpadding="0" cellspacing="0" width="100%">');
if($pos1===false) $pos1=strpos($data,'<div class="normal_header">Search Results</div>');
if($pos1===false) $pos1=strpos($data,'<table border="0" cellpadding="0" cellspacing="0" width="100%">');
if($pos1===false) $pos1=strpos($data,'Search Results');
$data=substr($data,$pos1);
$pos1=strpos($data,'<div id="rightcontentunder">');
$data=substr($data,0,$pos1);
var_dump($data);die();
//only search elements

if($title=='Anime - MyAnimeList.net') {

	
	$pos=0;
	$nb=substr_count($data,'<tr>');
	$nb--;// not counting header
	if($debug) var_dump($nb);

	for($i=0;$i<$nb;$i++) {
		echo "\r\n";
		$pos1=strpos($data,'<a href="http://myanimelist.net/anime/',$pos);
		$pos1+=9;
		$pos2=strpos($data,'"',$pos1);
		$link=substr($data,$pos1,$pos2-$pos1);
		if($debug) var_dump($link);

		$pos=$pos2+1;
		$pos1=strpos($data,'<strong>',$pos);
		$pos1+=8;
		$pos2=strpos($data,'</strong>',$pos1);
		$name=substr($data,$pos1,$pos2-$pos1);
		if($debug) var_dump($name);

		$pos=$pos2+9;
		$pos1=strpos($data,'<div style="margin-top: 3px;" class="lightLink">',$pos);
		$pos1+=strlen('<div style="margin-top: 3px;" class="lightLink">');
		$pos2=strpos($data,'</div>',$pos1);
		$genre=trim(substr($data,$pos1,$pos2-$pos1));
		if($debug) var_dump($genre);

		$pos=$pos2+6;
		$pos1=strpos($data,'<div class="spaceit">',$pos);
		$pos1+=strlen('<div class="spaceit">');
		$pos2=strpos($data,'<a',$pos1);
		$pos2b=strpos($data,'</div>',$pos1);
		if($pos2===false||$pos2b<$pos2) $pos2=$pos2b;
		//var_dump($pos1,$pos2,$pos2b);
		$synopsis=trim(substr($data,$pos1,$pos2-$pos1));
		if($debug) var_dump($synopsis);

		// type, eps, note
		$pos=$pos2+6;
		$pos1=strpos($data,'<td class="borderClass bgColor" align="center">',$pos);
		$pos1+=strlen('<td class="borderClass bgColor" align="center">');
		$pos2=strpos($data,'</td>',$pos1);
		$type=substr($data,$pos1,$pos2-$pos1);
		if($debug) var_dump($type);

		$pos=$pos2+5;
		$pos1=strpos($data,'<td class="borderClass bgColor" align="center">',$pos);
		$pos1+=strlen('<td class="borderClass bgColor" align="center">');
		$pos2=strpos($data,'</td>',$pos1);
		$eps=substr($data,$pos1,$pos2-$pos1);
		if($debug) var_dump($eps);

		$pos=$pos2+5;
		$pos1=strpos($data,'<td class="borderClass bgColor" align="center">',$pos);
		$pos1+=strlen('<td class="borderClass bgColor" align="center">');
		$pos2=strpos($data,'</td>',$pos1);
		$note=substr($data,$pos1,$pos2-$pos1);
		if($debug) var_dump($note);

		$pos=$pos2+5;
		if($debug) echo "\r\n";
	}

}
// single anime
else {
	
	$pos1=strpos($data,'<h1>');
	$pos2=strpos($data,'</h1>',$pos1);
	$pos1b=strpos($data,'</div>',$pos1);
	if($pos1b!==false&&$pos1b<$pos2) $pos1=$pos1b+6;
	$name=substr($data,$pos1,$pos2-$pos1);
	if($debug) var_dump($name);
	
	$pos=$pos2+5;
	$pos1=strpos($data,'<div><span class="dark_text">Type:</span> ',$pos);
	$pos1+=strlen('<div><span class="dark_text">Type:</span> ');
	$pos2=strpos($data,'</div>',$pos1);
	$type=trim(substr($data,$pos1,$pos2-$pos1));
	if($debug) var_dump($type);
	
	$pos=$pos2+6;
	$pos1=strpos($data,'<div class="spaceit"><span class="dark_text">Episodes:</span> ',$pos);
	$pos1+=strlen('<div class="spaceit"><span class="dark_text">Episodes:</span> ');
	$pos2=strpos($data,'</div>',$pos1);
	$eps=trim(substr($data,$pos1,$pos2-$pos1));
	if($debug) var_dump($eps);
	
	$pos=$pos2+6;
	$pos1=strpos($data,'<span class="dark_text">Genres:</span> ',$pos);
	$pos1+=strlen('<span class="dark_text">Genres:</span> ');
	$pos2=strpos($data,'</div>',$pos1);
	$genre=trim(strip_tags(substr($data,$pos1,$pos2-$pos1)));
	if($debug) var_dump($genre);
	
	$pos=$pos2+6;
	$pos1=strpos($data,'<span class="dark_text">Score:</span> ',$pos);
	$pos1+=strlen('<span class="dark_text">Score:</span> ');
	//$pos2=strpos($data,'</div>',$pos1);
	$pos2=strpos($data,' ',$pos1);
	$note=trim(strip_tags(substr($data,$pos1,$pos2-$pos1)));
	if($debug) var_dump($note);
	
	$pos=$pos2+6;
	$pos1=strpos($data,'<ul style="margin-right: 0; padding-right: 0;">',$pos);
	$pos1+=strlen('<ul style="margin-right: 0; padding-right: 0;">');
	$pos1=strpos($data,'<li>',$pos1);
	$pos1+=4;
	$pos1=strpos($data,'<a href="',$pos1);
	$pos1+=9;
	$pos2=strpos($data,'"',$pos1);
	$link=substr($data,$pos1,$pos2-$pos1);
	if($debug) var_dump($link);
	
	$pos1=strpos($data,'<h2>Synopsis</h2>',$pos);
	$pos1+=strlen('<h2>Synopsis</h2>');
	$pos2=strpos($data,'</td>',$pos1);
	$synopsis=trim(strip_tags(substr($data,$pos1,$pos2-$pos1)));
	if($debug) var_dump($synopsis);
}
?>
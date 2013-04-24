<?php
function search($search,$debug=false,$retry=5) {
	global $count_;
	$animes=array();
	$a=0;
	$data=file_get_contents('http://myanimelist.net/anime.php?q='.rawurlencode($search));
	echo __LINE__;var_dump(rawurlencode($search));

start:
	$pos1=strpos($data,'<title>');
	$pos1+=strlen('<title>');
	$pos2=strpos($data,'</title>',$pos1);
	$title=substr($data,$pos1,$pos2-$pos1);

	//var_dump($data);die();
	
	/*var_dump($title);
	echo "\r\n";//*/

	$pos1=strpos($data,'<div class="borderClass" style="text-align: right; margin: 5px 0; border-width: 0;">Pages (1) </div><table border="0" cellpadding="0" cellspacing="0" width="100%">');
	if($pos1===false) $pos1=strpos($data,'Search Results');
	if($pos1===false) $pos1=strpos($data,'<div class="normal_header">Search Results</div>');
	if($pos1===false) $pos1=strpos($data,'<div id="rightbody">');
	if($pos1===false) $pos1=strpos($data,'<div id="contentWrapper">');
	if($pos1===false) $pos1=strpos($data,'<table border="0" cellpadding="0" cellspacing="0" width="100%">');
	if($pos1===false) {
		if(strpos($data,'Error searching, try again or contact Xinil.')!==false) {
			++$count_;
			var_dump($retry.' attempt left');
			if($retry>0)
				return search($search,$debug,$retry-1);
			else
				die("error after retrying ".$retry." times");
		}
		var_dump(NULL);
		var_dump($data);
		die("error");
		$debug=true;
	}
	$data=substr($data,$pos1);
	//$pos1=strpos($data,'<div id="rightcontentunder">');
	$pos1=strpos($data,'<div id="rightcontentunder">');
	$data=substr($data,0,$pos1);
	echo __LINE__;var_dump($title);//die();
	//var_dump($data);//die();
	//only search elements

	$i=0;

	if($title=='Anime - MyAnimeList.net') {
		//var_dump(__LINE__);
		
		$pos=0;
		$nb=substr_count($data,'<tr>');
		//$nb--;// not counting header
		if($debug) { echo __LINE__;var_dump($nb); }

		for($i=0;$i<$nb;$i++) {
			if($debug) echo "\r\n";
			else $animes[$a+$i]=array();
			$pos1=strpos($data,'<a href="http://myanimelist.net/anime/',$pos);
			$pos1+=9;
			$pos2=strpos($data,'"',$pos1);
			$link=substr($data,$pos1,$pos2-$pos1);
			if($debug) { echo __LINE__;var_dump($link); }
			else $animes[$a+$i]['link']=$link;

			$pos=$pos2+1;
			$pos1=strpos($data,'<strong>',$pos);
			$pos1+=8;
			$pos2=strpos($data,'</strong>',$pos1);
			$name=substr($data,$pos1,$pos2-$pos1);
			if($debug) { echo __LINE__;var_dump($name); }
			else $animes[$a+$i]['name']=$name;

			$pos=$pos2+9;
			$pos1=strpos($data,'<div style="margin-top: 3px;" class="lightLink">',$pos);
			$pos1+=strlen('<div style="margin-top: 3px;" class="lightLink">');
			$pos2=strpos($data,'</div>',$pos1);
			$genre=trim(substr($data,$pos1,$pos2-$pos1));
			if($debug) { echo __LINE__;var_dump($genre); }
			else $animes[$a+$i]['genre']=$genre;

			$pos=$pos2+6;
			$pos1=strpos($data,'<div class="spaceit">',$pos);
			$pos1+=strlen('<div class="spaceit">');
			$pos2=strpos($data,'<a',$pos1);
			$pos2b=strpos($data,'</div>',$pos1);
			if($pos2===false||$pos2b<$pos2) $pos2=$pos2b;
			//var_dump($pos1,$pos2,$pos2b);
			$synopsis=trim(substr($data,$pos1,$pos2-$pos1));
			if($debug) { echo __LINE__;var_dump($synopsis); }
			else $animes[$a+$i]['synopsis']=$synopsis;

			// type, eps, note
			$pos=$pos2+6;
			$pos1=strpos($data,'<td class="borderClass bgColor" align="center">',$pos);
			$pos1+=strlen('<td class="borderClass bgColor" align="center">');
			$pos2=strpos($data,'</td>',$pos1);
			$type=substr($data,$pos1,$pos2-$pos1);
			if($debug) { echo __LINE__;var_dump($type); }
			else $animes[$a+$i]['type']=$type;

			$pos=$pos2+5;
			$pos1=strpos($data,'<td class="borderClass bgColor" align="center">',$pos);
			$pos1+=strlen('<td class="borderClass bgColor" align="center">');
			$pos2=strpos($data,'</td>',$pos1);
			$eps=substr($data,$pos1,$pos2-$pos1);
			if($debug) { echo __LINE__;var_dump($eps); }
			else $animes[$a+$i]['eps']=$eps;

			$pos=$pos2+5;
			$pos1=strpos($data,'<td class="borderClass bgColor" align="center">',$pos);
			$pos1+=strlen('<td class="borderClass bgColor" align="center">');
			$pos2=strpos($data,'</td>',$pos1);
			$note=substr($data,$pos1,$pos2-$pos1);
			if($debug) { echo __LINE__;var_dump($note); }
			else $animes[$a+$i]['note']=$note;

			$pos=$pos2+5;
			if($debug) echo "\r\n";
		}
		preg_match('#<a href="([^"]+)"> Next &raquo;</a>#', $data, $matches);
		if(count($matches)==2) {
			//var_dump($matches[1]);die();
			$a+=$nb;
			$data=file_get_contents('http://myanimelist.net/anime.php'.$matches[1]);
			goto start;
		}
	}
	// single anime
	else {
		//var_dump(__LINE__);
		
		$pos1=strpos($data,'<h1>');
		$pos2=strpos($data,'</h1>',$pos1);
		$pos1b=strpos($data,'</div>',$pos1);
		if($pos1b!==false&&$pos1b<$pos2) $pos1=$pos1b+6;
		$name=substr($data,$pos1,$pos2-$pos1);
		//var_dump(NULL,$data,NULL,$pos1,$pos2,$pos1b,$name,NULL);
		if($debug) { echo __LINE__;var_dump($name); }
		else $animes[$i]['name']=$name;
		
		$pos=$pos2+5;
		$pos1=strpos($data,'<div><span class="dark_text">Type:</span> ',$pos);
		$pos1+=strlen('<div><span class="dark_text">Type:</span> ');
		$pos2=strpos($data,'</div>',$pos1);
		$type=trim(substr($data,$pos1,$pos2-$pos1));
		if($debug) { echo __LINE__;var_dump($type); }
		else $animes[$i]['type']=$type;
		
		$pos=$pos2+6;
		$pos1=strpos($data,'<div class="spaceit"><span class="dark_text">Episodes:</span> ',$pos);
		$pos1+=strlen('<div class="spaceit"><span class="dark_text">Episodes:</span> ');
		$pos2=strpos($data,'</div>',$pos1);
		$eps=trim(substr($data,$pos1,$pos2-$pos1));
		if($debug) { echo __LINE__;var_dump($eps); }
		else $animes[$i]['eps']=$eps;
		
		$pos=$pos2+6;
		$pos1=strpos($data,'<span class="dark_text">Genres:</span>',$pos);
		$pos1+=strlen('<span class="dark_text">Genres:</span>');
		$pos2=strpos($data,'</div>',$pos1);
		$genre=trim(strip_tags(substr($data,$pos1,$pos2-$pos1)));
		if($debug) { echo __LINE__;var_dump($pos1,$pos2);var_dump($genre); }
		else $animes[$i]['genre']=$genre;
		
		$pos=$pos2+6;
		$pos1=strpos($data,'<span class="dark_text">Score:</span> ',$pos);
		$pos1+=strlen('<span class="dark_text">Score:</span> ');
		//$pos2=strpos($data,'</div>',$pos1);
		$pos2=strpos($data,' ',$pos1);
		$note=trim(strip_tags(substr($data,$pos1,$pos2-$pos1)));
		$nbs_=strpos($data, '(scored by ', $pos2);
		$nbs_+=strlen('(scored by ');
		$nbs2=strpos($data, ' users', $nbs_);
		$nbs=substr($data, $nbs_, $nbs2-$nbs_);
		//var_dump($nbs);
		if($nbs==0) $note='-';
		if($debug) { echo __LINE__;var_dump($note); }
		else $animes[$i]['note']=$note;
		
		$pos=$pos2+6;
		$pos1=strpos($data,'<ul style="margin-right: 0; padding-right: 0;">',$pos);
		$pos1+=strlen('<ul style="margin-right: 0; padding-right: 0;">');
		$pos1=strpos($data,'<li>',$pos1);
		$pos1+=4;
		$pos1=strpos($data,'<a href="',$pos1);
		$pos1+=9;
		$pos2=strpos($data,'"',$pos1);
		$link=substr($data,$pos1,$pos2-$pos1);
		if($debug) { echo __LINE__;var_dump($link); }
		else $animes[$i]['link']=$link;
		
		$pos1=strpos($data,'<h2>Synopsis</h2>',$pos);
		$pos1+=strlen('<h2>Synopsis</h2>');
		$pos2=strpos($data,'</td>',$pos1);
		$synopsis=trim(strip_tags(substr($data,$pos1,$pos2-$pos1)));
		if($debug) { echo __LINE__;var_dump($synopsis); }
		else $animes[$i]['synopsis']=$synopsis;
	}
	return $animes;
}
/*
header('Content-Type: text/plain'."\r\n");
if(!array_key_exists('search',$_REQUEST)) $search='ikkitousen'; else $search=$_REQUEST['search'];
search($search,true);
*/
?>
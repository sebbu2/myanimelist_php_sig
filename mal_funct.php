<?php
require('cred.php');
$opts=array(
        'http'=>array(
                'user_agent'=>$useragent,
        ),
        'ssl'=>array(
                'allow_self_signed'=>true,
        ),
);
$opts['http']['header']='';
$opts['http']['header'].=$cred."\r\n";
$opts['http']['header'].='Cookie: '.
'__gads=ID=7c893b73ccfdbca8:T=1413443667:S=ALNI_MYeDycs21kipgzAiGQT3WWs9Q_haA; '.
'incap_ses_258_81958=FW2+GtjMw214X3P+xJmUA3CBP1QAAAAAISU7VavQwD9Byb/x8XJopQ==; '.
'incap_ses_32_81958=1jOAJaqwvVYTzAWARLFxABhxP1QAAAAAm/F+fsjGStRm//o2hwFKhg==; '.
'visid_incap_81958=pbEXRmbbSous/15TL6/n01BwP1QAAAAAQUIPAAAAAACyfcGV50uD2QD0QpUm/E1W; '.
'___utmvc=navigator%3Dobject,navigator.vendor%3D,opera%3DReferenceError%3A%20opera%20is%20not%20defined,ActiveXObject%3DReferenceError%3A%20ActiveXObject%20is%20not%20defined,navigator.appName%3DNetscape,plugin%3Ddll,webkitURL%3DReferenceError%3A%20webkitURL%20is%20not%20defined,navigator.plugins.length%3D%3D0%3Dfalse,digest=31678,31997; '.
'gn_country=US; '.
'visitor_country=FR; '.
'noticeShown=true; '.
"\r\n";
$ctx = stream_context_create($opts);
function search($search,$debug=false,$retry=5) {
	global $count_;
	global $opts,$ctx;
	$animes=array();
	$a=0;
	$data=file_get_contents('http://myanimelist.net/anime.php?q='.rawurlencode($search),false,$ctx);
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
			if($pos1===false) $pos1=strpos($data,'<a href="/anime/',$pos);
			$pos1+=9;
			$pos2=strpos($data,'"',$pos1);
			$link=substr($data,$pos1,$pos2-$pos1);
			if(substr($link,0,7)!='http://') $link='http://myanimelist.net'.$link;
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
			$data=file_get_contents('http://myanimelist.net/anime.php'.$matches[1],false,$ctx);
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
libxml_use_internal_errors(true);
function search2($search, $debug=false, $retry=5) {
	global $count_;
	global $opts,$ctx;
	$animes=array();
	$a=0;
	//$data=file_get_contents('http://myanimelist.net/anime.php?q='.rawurlencode($search),false,$ctx);
	$data=file_get_contents('http://myanimelist.net/api/anime/search.xml?q='.rawurlencode($search),false,$ctx);
	echo __LINE__;var_dump(rawurlencode($search));
	$data = html_entity_decode($data);
	$sxe = simplexml_load_string($data);
	//$sxe = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOENT);
	if($sxe===false) {
		foreach(libxml_get_errors() as $error) {
			echo "\t", $error->message.'<br/>';
		}
		var_dump($data);
		die('error in reading xml');
		return array();
	}
	$i=0;
	foreach($sxe->entry as $anime) {
		$animes[$i]=array();
		$animes[$i]['link']='http://myanimelist.net/anime/'.(string)$anime->id;
		$animes[$i]['name']=(string)$anime->title;
		$animes[$i]['alternates']=explode(';', $anime->synonyms);
		$animes[$i]['alternates']=array_map('trim', $animes[$i]['alternates']);
		$animes[$i]['alternates']=array_map('html_entity_decode', $animes[$i]['alternates']);
		$animes[$i]['alternates'][]=trim(html_entity_decode($anime->english));
		//$animes[$i]['genre']='';//missing
		$animes[$i]['synopsis']=(string)$anime->synopsis;
		$animes[$i]['type']=(string)$anime->type;
		$animes[$i]['eps']=(string)$anime->episodes;
		$animes[$i]['note']=(string)$anime->score;
		++$i;
	}
	return $animes;
}
/*
header('Content-Type: text/plain'."\r\n");
if(!array_key_exists('search',$_REQUEST)) $search='ikkitousen'; else $search=$_REQUEST['search'];
search($search,true);
*/
?>
<?php
//指定された、はてブフィードを解析してHTMLとして返す
if(isset($_GET['id']) && $_GET['id'] != ''){
	$htebuId = $_GET['id'];
	$url = 'http://b.hatena.ne.jp/' . $htebuId . '/rss?date=' . date('Ymd');
	$xml = simplexml_load_file($url);
	
	$output = '<ul>';
	foreach($xml->item as $val){
		//コンテント部分を取得する場合
		/*
		$dc = $val->children('http://purl.org/rss/1.0/modules/content/');
		echo $dc->encoded;
		*/
		
		$output .= <<<EOM
<li><a href="{$val->link}" target="_blank">{$val->title}</a><br />{$val->description}<br /><br /></li>

EOM;

	}
	$output .= '</ul>';
	
	echo $output;
}

<?php
function feed2pc($feed){
	//error_reporting(2147483647);
	$feed_str=file_get_contents($feed);
	$sxe = new SimpleXMLElement($feed_str);
	foreach ($sxe->channel->item as $item){
		$page=file_get_contents($item->link);
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		$xpath = new DOMXPath($dom);
		$hrefs = $xpath->evaluate("/html/body//a");
		for ($i = 0; $i < $hrefs->length; $i++) {
			$href = $hrefs->item($i);
			$url = $href->getAttribute('href');
			if(substr($url,-4)=='.mp3') {
				$file=$url;
				break;
			}
		}
		$enclosure=$item->addChild('enclosure');
		$enclosure->addAttribute('url',$file);
		$enclosure->addAttribute('type','mp3');
	}
	echo $sxe->asXML();
}

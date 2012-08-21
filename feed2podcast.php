<?php

class PodcastFeedCreator {
    public function __construct($feed) {
        echo $this->processFeed($feed);
    }
    
    public function processFeed($feed) {
    	$feed_str=file_get_contents($feed);
	    $sxe = new SimpleXMLElement($feed_str);
	    foreach ($sxe->channel->item as $item) {
	        $file = $this->findEnclosureLink($item->link);
    		$enclosure=$item->addChild('enclosure');
	    	$enclosure->addAttribute('url',$file);
	    	$enclosure->addAttribute('type','mp3');
		}
		
		return $sxe->asXML();
	
    }
    
    public function findEnclosureLink($item) {
        $page=file_get_contents($item);
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		$xpath = new DOMXPath($dom);
		$hrefs = $xpath->evaluate("/html/body//a");
		for ($i = 0; $i < $hrefs->length; $i++) {
			$href = $hrefs->item($i);
			$url = $href->getAttribute('href');
			if(substr($url,-4)=='.mp3') {
				return $url;
			}
        }
    }
}

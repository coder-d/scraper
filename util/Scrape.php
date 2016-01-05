<?php
class Scrape{
	
	public function getRemoteData($title){
		$amazonContent = $this->_amazonData($title);
		$ebayContent = $this->_ebayData($title);
		$mergedContent = array_merge($amazonContent, $ebayContent);
		return $mergedContent;
	}

	private function _amazonData($title){
		$contents = $this->_remoteCall('amazon',$title);
		// DOM document Creation
		$dom = new DOMDocument;
		@$dom->loadHTML($contents);
		$xpath = new DOMXPath($dom);
		$links = @$xpath->query('//*[contains(concat(" ", normalize-space(@id), " "), "s-results-list-atf")]//li');
		$fetchedData = array();
		for ($i = 0; $i < ($links->length); $i++){
		    $itemLink = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), "s-access-detail-page")]', $links->item($i));
		    $linkTitle = @$itemLink->item($i)->nodeValue;
		    if(is_object($itemLink->item($i))){
			    $linkHref = @$itemLink->item($i)->getAttribute('href');
			    $itemPrice = @$xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), "s-price")]', $links->item($i));
			    $price = @$itemPrice->item($i)->nodeValue;
			    $fetchedData[$i.'amazon']['title'] = html_entity_decode($linkTitle);
			    $fetchedData[$i.'amazon']['href'] = $linkHref;
			    $fetchedData[$i.'amazon']['price'] = $price;
		    }			
		}
		return $fetchedData;
	}

	private function _ebayData($title){
		$contents = $this->_remoteCall('ebay',$title);
		// DOM document Creation
		$dom = new DOMDocument;
		@$dom->loadHTML($contents);
		$xpath = new DOMXPath($dom);
		$links = @$xpath->query('//*[contains(concat(" ", normalize-space(@id), " "), "ListViewInner")]//li
		    	[contains(concat(" ", normalize-space(@class), " "), "sresult")]');
		$fetchedData = array();
		for ($i = 0; $i < ($links->length); $i++){
		    $itemLink = @$xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), "lvtitle")]//a', $links->item($i));
		    if(is_object($itemLink->item($i))){
		    	$linkTitle = @$itemLink->item($i)->nodeValue;
		    	$linkHref = @$itemLink->item($i)->getAttribute('href');	
		    	$itemPrice = @$xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), "lvprice")]//
		    	span[contains(concat(" ", normalize-space(@class), " "), "bold")][1]', $links->item($i));
			    $price = @$itemPrice->item($i)->nodeValue;
			    $fetchedData[$i.'ebay']['title'] = html_entity_decode($linkTitle);
			    $fetchedData[$i.'ebay']['href'] = $linkHref;
			    $fetchedData[$i.'ebay']['price'] = $price;	
		    }		
		}
		return $fetchedData;
	}

	private function _remoteCall($site,$title){
		$title = str_replace(' ','+',$title);
		switch($site) {
			case 'amazon' :
			$url = 'http://www.amazon.com/s/?field-keywords='.$title;
			break;
			case 'ebay' :
			$url = 'http://www.ebay.com/sch/i.html?_nkw='.$title;
			break;
		}
		// Get the HTML Source Code
		$contents= file_get_contents($url);
		    return $contents;
	}
}
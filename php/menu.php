<?php
	include_once('simple_html_dom.php');

	// $url = 'http://www.yelp.com/biz/canoe-atlanta-2';
	// $url1 = 'http://www.yelp.com/biz/aviva-by-kameel-atlanta';
	// $url2 = 'http://www.yelp.com/biz/one-flew-south-atlanta';
	// $url3 = 'http://www.yelp.com/biz/purnima-atlanta';
	
	// $html = file_get_html($url3);
	// $menu_url = getMenuURL($html);
	// $menu_items = getMenuItems($menu_url);
	// foreach($menu_items as $menu_item)
		// echo $menu_item.'<br>';
	
	function getMenuURL($html) {
		$menu_url = false;
		foreach($html->find('a') as $element)
			if(strcmp($element->class, 'menu-explore') == 0) {
				$menu_url = 'http://www.yelp.com' . $element->href;
			} elseif(strpos($element->class, 'external-menu') !== false) {
				// $menu_url = $element->href;
				$menu_url = false;
			}
		return $menu_url;
	}
	
	function getMenuItems($menu_url) {
		if($menu_url !== false) {
			$menu_html = file_get_html($menu_url);
			$index = 0;
			foreach($menu_html->find('h3') as $element) {
				$menu_items[$index] = $element;
				$index = $index + 1;
				// echo $menu_items[$index - 1].'<br>';
			}
			array_splice($menu_items, $index - 6, 6);
			$index = 0;
			foreach($menu_items as $menu_item) {
				$indexOfFrontOfOpenAnchorTag = strpos($menu_item, '<a');
				$menu_item = substr($menu_item, $indexOfFrontOfOpenAnchorTag+1);
				if($indexOfFrontOfOpenAnchorTag !== false) { //isLink
					$indexOfEndOfOpenAnchorTag = strpos($menu_item, '>');
					$indexOfFrontOfCloseAnchorTag = strpos($menu_item, '</');
					$menu_item = substr($menu_item, $indexOfEndOfOpenAnchorTag+1, $indexOfFrontOfCloseAnchorTag-$indexOfEndOfOpenAnchorTag-1);
				} else { //!isLink
					$indexOfEndOfOpenHeaderTag = strpos($menu_item, '>');
					$indexOfFrontOfCloseHeaderTag = strpos($menu_item, '</');
					$menu_item = substr($menu_item, $indexOfEndOfOpenHeaderTag+1, $indexOfFrontOfCloseHeaderTag-$indexOfEndOfOpenHeaderTag-1);
				}
				$menu_items[$index] = $menu_item;
				$index = $index + 1;
			}
			return $menu_items;
		} else {
			return false;
		}
	}
?>
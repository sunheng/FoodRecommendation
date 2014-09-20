<?php
	include('simple_html_dom.php');

	$url = 'http://www.yelp.com/biz/canoe-atlanta-2';
	$url1 = 'http://www.yelp.com/biz/aviva-by-kameel-atlanta';
	$url2 = 'http://www.yelp.com/biz/one-flew-south-atlanta';
	$url3 = 'http://www.yelp.com/biz/purnima-atlanta';
	
	$html = file_get_html($url3);
	// getMenuItems(getMenuURL($html));
	
	// function getRecommendedItems($url, $numberOfReviews, $menu) {
		// $html = file_get_html($url);

		// foreach($html->find('p') as $element)
		
			// $index = 0;
			// while(!$index && $index != strlen($element)) {
			
			// }
	// }

	// function checkElementForMenuItem($element, $menu) {
		// foreach($menu)
	// }
	
	function getMenuURL($html) {
		$menu_url = false;
		foreach($html->find('a') as $element)
			if(strcmp($element->class, 'menu-explore') == 0) {
				$menu_url = 'http://www.yelp.com' . $element->href;
			} elseif(strpos($element->class, 'external-menu') !== false) {
				$menu_url = $element->href;
			}
		return $menu_url;
	}
	
	function getMenuItems($menu_url) {
		$menu_html = file_get_html($menu_url);
		$index = 0;
		foreach($menu_html->find('h3') as $element) {
			$menu_items[$index] = $element;
			$index = $index + 1;
		}
		array_splice($menu_items, $index - 6, 6);
		$index = 0;
		foreach($menu_items as $menu_item) {
			$indexOfFrontOfOpenAnchorTag = strpos($menu_item, '<a');
			$menu_item = substr($menu_item, $indexOfFrontOfOpenAnchorTag+1);
			if($indexOfFrontOfOpenAnchorTag !== false) {
				$indexOfEndOfOpenAnchorTag = strpos($menu_item, '>');
				$indexOfFrontOfCloseAnchorTag = strpos($menu_item, '</');
				$menu_item = substr($menu_item, $indexOfEndOfOpenAnchorTag+1, $indexOfFrontOfCloseAnchorTag-$indexOfEndOfOpenAnchorTag-1);
			} else {
				$indexOfEndOfOpenHeaderTag = strpos($menu_item, '>');
				$indexOfFrontOfCloseHeaderTag = strpos($menu_item, '</');
				$menu_item = substr($menu_item, $indexOfEndOfOpenHeaderTag+1, $indexOfFrontOfCloseAnchorTag-$indexOfEndOfOpenHeaderTag-1);
			}
			$menu_items[$index] = $menu_item;
			$index = $index + 1;
		}
		// Slight bug: too many things
		// foreach($menu_items as $menu_item)
			// echo $menu_item . '<br>';
		return $menu_item;
	}
?>
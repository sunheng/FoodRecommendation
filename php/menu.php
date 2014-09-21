<?php
	include_once('simple_html_dom.php');
	
	function getMenuURL($html) {
		$menu_url = false;
		$element = $html->find('a[class=menu-explore]');
		if(count($element) > 0) {
			$menu_url = 'http://www.yelp.com' . $element[0]->href;
		}
		return $menu_url;
	}
	
	function getMenuItems($menu_url) {
		if($menu_url !== false) {
			$menu_html = file_get_html($menu_url);
			$index = 0;
			foreach($menu_html->find('h3') as $element) {
				$menu_item = $element;
				$indexOfFrontOfOpenAnchorTag = strpos($menu_item, '<a');
				$menu_item = substr($menu_item, $indexOfFrontOfOpenAnchorTag+1);
				if($indexOfFrontOfOpenAnchorTag !== false) {
					$indexOfEndOfOpenAnchorTag = strpos($menu_item, '>');
					$indexOfFrontOfCloseAnchorTag = strpos($menu_item, '</');
					$menu_item = substr($menu_item, $indexOfEndOfOpenAnchorTag+1, $indexOfFrontOfCloseAnchorTag-$indexOfEndOfOpenAnchorTag-1);
				} else {
					$indexOfEndOfOpenHeaderTag = strpos($menu_item, '>');
					$indexOfFrontOfCloseHeaderTag = strpos($menu_item, '</');
					$menu_item = substr($menu_item, $indexOfEndOfOpenHeaderTag+1, $indexOfFrontOfCloseHeaderTag-$indexOfEndOfOpenHeaderTag-1);
				}
				$indexOfDot = strpos($menu_item, '.');
				if($indexOfDot !== false) {
					$menu_item = substr($menu_item, $indexOfDot + 1);
				}
				$menu_items[$index] = $menu_item;
				$index = $index + 1;
			}
			array_splice($menu_items, $index - 6, 6);
			return $menu_items;
		} else {
			return false;
		}
	}
?>
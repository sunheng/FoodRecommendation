<?php
	include_once('simple_html_dom.php');
	include('menu.php');
	
	$url = $_POST['url'];
	$numberOfReviews = $_POST['reviewCount'];
	
	get_recommended_items($url, $numberOfReviews);
	
	function get_recommended_items($url, $numberOfReviews) {
		$html_page = file_get_html($url);
		$all_reviews = extract_all_comments($html_page);
		$menu_url = getMenuURL($html_page);
		$menu_items = getMenuItems($menu_url);
		if($menu_items !== false) {
			$menu_items_occurences;
			for($i = 0; $i <= floor($numberOfReviews/40); $i++){
				$temp = $url . '?start=' . ($i*40);
				$html_page = file_get_html($temp);
				$all_reviews = extract_all_comments($html_page);
				$comments = strtolower(implode(' ', $all_reviews));
				$index = 0;
				foreach($menu_items as $menu_item) {
					$menu_item = strtolower($menu_item);
					$numberOfOccurences = substr_count($comments, $menu_item);
					if($numberOfOccurences != 0 & strlen($menu_item) >= 4) {
						$menu_items_occurences[$menu_item] += $numberOfOccurences;
					}
				}
			}
			asort($menu_items_occurences);
			$recommended_items = '{';
			if(!empty($menu_items_occurences) | !$menu_items_occurences) {
				$length = count($menu_items_occurences);
				$keys = array_keys($menu_items_occurences);
				$values = array_values($menu_items_occurences);
				if($length >= 3) {
					$recommended_items .= '"' . $keys[$length-1] . '": ' . $values[$length-1] . ', "' . $keys[$length-2] . '": ' . $values[$length-2] . ', "' . $keys[$length-3] . '": ' . $values[$length-3] . '}';
				} else if($length == 2) {
					$recommended_items .= '"' . $keys[$length-1] . '": ' . $values[$length-1] . ', "' . $keys[$length-2] . '": ' . $values[$length-2] . '}';
				} else {
					$recommended_items .= '"' . $keys[$length-1] . '": ' . $values[$length-1] . '}';
				}
			} else {
				$recommended_items .= '}';
			}
		} else {
			$typesOfFood = array('bacon', 'burrito', 'taco', 'burger', 'pizza', 'beef', 'chicken', 'pork', 'lamb', 'tuna', 'salmon', 'veggie', 'vegetarian', 'tofu');
			$typesOfFood_occurences;
			for($i = 0; $i <= floor($numberOfReviews/40); $i++){
				$temp = $url . '?start=' . ($i*40);
				$html_page = file_get_html($temp);
				$all_reviews = extract_all_comments($html_page);
				$comments = strtolower(implode(' ', $all_reviews));
				$index = 0;
				foreach($typesOfFood as $typeOfFood) {
					$numberOfOccurences = substr_count($comments, $typeOfFood);
					if($numberOfOccurences != 0) {
						$typesOfFood_occurences[$typeOfFood] += $numberOfOccurences;
					}
				}
			}
			asort($typesOfFood_occurences);
			$recommended_items = '{';
			if(!empty($typesOfFood_occurences)) {
				$length = count($typesOfFood_occurences);
				$keys = array_keys($typesOfFood_occurences);
				$values = array_values($typesOfFood_occurences);
				if($length >= 3) {
					$recommended_items .= '"' . $keys[$length-1] . '": ' . $values[$length-1] . ', "' . $keys[$length-2] . '": ' . $values[$length-2] . ', "' . $keys[$length-3] . '": ' . $values[$length-3] . '}';
				} else if($length == 2) {
					$recommended_items .= '"' . $keys[$length-1] . '": ' . $values[$length-1] . ', "' . $keys[$length-2] . '": ' . $values[$length-2] . '}';
				} else {
					$recommended_items .= '"' . $keys[$length-1] . '": ' . $values[$length-1] . '}';
				}
			} else {
				$recommended_items .= '}';
			}
		}
		echo $recommended_items;
	}
	
	function get_links($html_page) {
		$a_array = $html_page->find('a[class=ngram]');
		$index = 0;
		foreach($a_array as $a) {
			if(strlen($a) >= 8) {
				$indexOfEndOfOpenAnchorTag = strpos($a, '>');
				$indexOfFrontOfEndAnchorTag = strpos($a, '</');
				$a = substr($a, $indexOfEndOfOpenAnchorTag+1, $indexOfFrontOfEndAnchorTag-$indexOfEndOfOpenAnchorTag-1);
				$a_array[$index] = $a;
				$index += 1;
			}
		}
		return $a_array;
	}
	
	function extract_all_comments($html_page) {
		$review_class = 'review-content';
		$div_array = $html_page->find('div[class=review-list]');
		$reviews = explode($review_class, $div_array[0]);
		
		$comment_delimiter = '<p class="review_comment ieSucks"';
		$rating_delimiter = '<i class="star-img';
		$clipped_comment = "";
		$clipped_rating = "";
		foreach($reviews as $review) {
			$rating_start_pos = strpos($review, $rating_delimiter);
			$rating = 0;
			if ($rating_start_pos !== false) {
				$unclipped_rating = substr($review, $rating_start_pos);
				$end_pos = strpos($unclipped_rating, '</i>') + strlen('</i>');
				$clipped_rating = substr($unclipped_rating, 0, $end_pos);
				// if (strpos($clipped_rating, 'stars_1') !== false) {
					// $rating = 1;
				// } else if (strpos($clipped_rating, 'stars_2') !== false) {
					// $rating = 2;
				// } else if (strpos($clipped_rating, 'stars_3') !== false) {
					// $rating = 3;
				// } else 
				if (strpos($clipped_rating, 'stars_4') !== false) {
					$rating = 4;
				} else if (strpos($clipped_rating, 'stars_4_half') !== false) {
					$rating = 4.5;
				} else if (strpos($clipped_rating, 'stars_5') !== false) {
					$rating = 5;
				}
			}
			
			if($rating != 0) {
				$start_pos = strpos($review, $comment_delimiter);
				if ($start_pos !== false) {
					$unclipped_comment = substr($review, $start_pos);
					$end_pos = strpos($unclipped_comment, '</p>') + strlen('</p>');
					$clipped_comment = substr($unclipped_comment, 0, $end_pos);
				}
				$all_reviews[] = $clipped_comment;
			}
		}
		unset($all_reviews[0]);
		return $all_reviews;
	}

	// class Review {
		// public $comment = '';
		// public function  __construct($comment) {
			// $this->comment = $comment;
		// }
	// }
?>
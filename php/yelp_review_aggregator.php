<?php
	include_once('simple_html_dom.php');
	include('menu.php');
	
	// $numberOfReviews = 41;
	// $url = 'http://www.yelp.com/biz/third-and-vine-jersey-city-2';
	$numberOfReviews = $_POST['reviewCount'];
	$url = $_POST['url'];
	$url1 = 'http://www.yelp.com/biz/california-pizza-kitchen-atlanta-2';
	$url2 = 'http://www.yelp.com/biz/bone-garden-cantina-atlanta';
	
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
				$comments = '';
				foreach($all_reviews as $review) {
					$comments .= $review->comment . ' ';
				}
				$comments = strtolower($comments);
				$index = 0;
				foreach($menu_items as $menu_item) {
					$menu_item = strtolower($menu_item);
					$numberOfOccurences = substr_count($comments, $menu_item);
					if($numberOfOccurences != 0) {
						$menu_items_occurences[$menu_item] += $numberOfOccurences;
					}
				}
			}
			$recommended_items = '{';
			$key_value = each($menu_items_occurences);
			while($key_value !== false) {
				if(strlen($recommended_items) > 1) {
					$recommended_items = substr($recommended_items, 0, count($recommended_items) - 2);
					$recommended_items .= ', "' . $key_value[0] . '": "' . $key_value[1] . '"}';
				} else {
					$recommended_items .= '"' . $key_value[0] . '": "' . $key_value[1] . '"}';
				}
				$key_value = each($menu_items_occurences);
			}
		} else {
			$typesOfFood = array('beef', 'chicken', 'pork', 'lamb', 'tuna', 'salmon', 'veggie', 'vegetarian', 'tofu');
			$typesOfFood_occurences;
			for($i = 0; $i <= floor($numberOfReviews/40); $i++){
				$temp = $url . '?start=' . ($i*40);
				$html_page = file_get_html($temp);
				$all_reviews = extract_all_comments($html_page);
				$comments = '';
				foreach($all_reviews as $review) {
					$comments .= $review->comment . ' ';
				}
				$comments = strtolower($comments);
				$index = 0;
				foreach($typesOfFood as $typeOfFood) {
					$numberOfOccurences = substr_count($comments, $typeOfFood);
					if($numberOfOccurences != 0) {
						$typesOfFood_occurences[$typeOfFood] += $numberOfOccurences;
					}
				}
			}
			$recommended_items = '{';
			$key_value = each($typesOfFood_occurences);
			while($key_value !== false) {
				if(strlen($recommended_items) > 1) {
					$recommended_items = substr($recommended_items, 0, count($recommended_items) - 2);
					$recommended_items .= ', "' . $key_value[0] . '": "' . $key_value[1] . '"}';
				} else {
					$recommended_items .= '"' . $key_value[0] . '": "' . $key_value[1] . '"}';
				}
				$key_value = each($typesOfFood_occurences);
			}
		}
		echo $recommended_items;
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
				$all_reviews[] = new Review($clipped_comment, $rating);
			}
		}
		unset($all_reviews[0]);
		return $all_reviews;
	}

	class Review {
		public $stars = 0;
		public $comment = '';
		public function  __construct($comment, $stars) {
			$this->comment = $comment;
			$this->stars = $stars;
		}
	}
?>
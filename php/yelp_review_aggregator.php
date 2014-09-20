<?php
	include_once('simple_html_dom.php');
	include('menu.php');
	
	$numberOfReviews = 121;
	$url = 'http://www.yelp.com/biz/third-and-vine-jersey-city-2';
	$url1 = 'http://www.yelp.com/biz/california-pizza-kitchen-atlanta-2';
	$url2 = 'http://www.yelp.com/biz/bone-garden-cantina-atlanta';
	
	get_recommended_items($url2, $numberOfReviews);
	
	function get_recommended_items($url, $numberOfReviews) {
		// $numberOfReviews /= 40;
		// echo ceil($numberOfReviews);
		$html_page = file_get_html($url);
		$all_reviews = extract_all_comments($html_page);
		$menu_url = getMenuURL($html_page);
		$menu_items = getMenuItems($menu_url);
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
			$recommended_items = '{';
			foreach($menu_items as $menu_item) {
				$menu_item = strtolower($menu_item);
				$numberOfOccurences = substr_count($comments, $menu_item);
				if($numberOfOccurences != 0) {
					// if(strlen($recommended_items) > 1) {
						// $recommended_items = substr($recommended_items, 0, count($recommended_items) - 2);
						// $recommended_items .= ', "' . $menu_item . '": "' . $numberOfOccurences . '"}';
					// } else {
						// $recommended_items .= '"' . $menu_item . '": "' . $numberOfOccurences . '"}';
					// }
					$menu_items_occurences[$menu_item] += $numberOfOccurences;
				}
			}
			// echo $recommended_items . '<br>';
		}
		print_r($menu_items_occurences);
	}
	
	function extract_all_comments($html_page) {
		$review_class = 'review-content';
		$div_array = $html_page->find('div[class=review-list]');
		$reviews = explode($review_class, $div_array[0]);
		
		$comment_delimiter = '<p class="review_comment ieSucks"';
		$rating_delimiter = '<i class="star-img';
		$clipped_comment = "";
		$clipped_rating = "";
		$rating = 0;
		foreach($reviews as $review) {
			$rating_start_pos = strpos($review, $rating_delimiter);
			if ($rating_start_pos !== false) {
				$unclipped_rating = substr($review, $rating_start_pos);
				$end_pos = strpos($unclipped_rating, '</i>') + strlen('</i>');
				$clipped_rating = substr($unclipped_rating, 0, $end_pos);
				if (strpos($clipped_rating, 'stars_1') !== false) {
					$rating = 1;
				} else if (strpos($clipped_rating, 'stars_2') !== false) {
					$rating = 2;
				} else if (strpos($clipped_rating, 'stars_3') !== false) {
					$rating = 3;
				} else if (strpos($clipped_rating, 'stars_4') !== false) {
					$rating = 4;
				} else if (strpos($clipped_rating, 'stars_5') !== false) {
					$rating = 5;
				}
			}
		
			$start_pos = strpos($review, $comment_delimiter);
			if ($start_pos !== false) {
				$unclipped_comment = substr($review, $start_pos);
				$end_pos = strpos($unclipped_comment, '</p>') + strlen('</p>');
				$clipped_comment = substr($unclipped_comment, 0, $end_pos);
			}
			$all_reviews[] = new Review($clipped_comment, $rating);
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
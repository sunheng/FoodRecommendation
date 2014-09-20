<?php
	include('simple_html_dom.php');
	
	$html_page = file_get_html('http://www.yelp.com/biz/third-and-vine-jersey-city-2');
	extract_all_comments($html_page);
	
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
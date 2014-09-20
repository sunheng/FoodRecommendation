<?php
	include('simple_html_dom.php');
	
	$html_page = file_get_html('http://www.yelp.com/biz/third-and-vine-jersey-city-2');
	$all_reviews = extract_all_comments($html_page);
	$i = 0;
	foreach ($all_reviews as $review) print $i++ . '<br />' . $review;
	print(strlen($all_reviews[1]));
	
	function extract_all_comments($html_page) {
		$all_reviews = array();
		$review_class = 'review_comment ieSucks';
		$end_pos = 0;
		foreach($html_page->find('p') as $element) {
			$start_pos = $end_pos + strpos($element, 'review_comment ieSucks') + strlen($review_class);
			if ($start_idx !== 0) {
				$unclipped_comment = substr($element, $start_pos);
				
				if (strpos($unclipped_comment, 'star-img stars_5') !== 0) echo 'yes';
				
				$end_pos = strpos($unclipped_comment, '</p>') + 1;
				$clipped_comment = substr($unclipped_comment, strpos($unclipped_comment, '>') + 1, $end_pos);
				$clean_comment = trim(str_replace('Â', '', $clipped_comment));
				if (strlen($clean_comment) !== 0) array_push($all_reviews, ($clean_comment));
			}
		}
		return $all_reviews;
	}
	
	class Review {
		public $rating = 0;
		public review = '';
	}
?>
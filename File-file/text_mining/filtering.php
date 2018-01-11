<?php
	// open stopword file
	$file = file_get_contents('stopwords.txt', FILE_USE_INCLUDE_PATH);

	// put stopword into array
	$stopwords = explode(',', $file);

	// show array of stopwords
	// foreach ($stopwords as $word) {
	// 	print_r($word);
	// }

	// open sentence file that want to be filtered
	$sentence = 'hari ini kami ada rapat osis';
	
	// put sentence into array
	$words = explode(" ",$sentence);

	// show array of sentence
	// foreach ($words as $word) {
	// 	print_r($word);
	// }

	// filtering
	$result = array_diff($words, $stopwords);

	//show array of filtered sentence
	print_r($result);
	
?>
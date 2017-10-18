<?php
	/**
	 * A function to read CSV file and returning an multidimensional array
	 * @param $csvFile is a path to the CSV file
	 */
	function OpenCsv($csvFile) {
		// open file
		$file = fopen($csvFile,'r');

		// create an array from csv file 
		while(! feof($file))
		{
			$array[] = fgetcsv($file);
		}

		// close file
		fclose($file);

		// returning multidimensional array
		return $array;
	}


	/**
	 * A function to tokenize sentences
	 * and store it into a bag of words
	 * @param $sentence is a sentence that wants to be tokenized
	 */
	function Tokenizing($sentence) {
		// using the global variable instead of local
		global $words;

		// tokenizing
		$token = explode(' ', $sentence);

		// store every word into the words array
		foreach ($token as $word) {
			$words[] = $word;
		}
	}

	/**
	 * A function to filtering a word that doesn't have a value
	 * @param $stopwordsFile is a path to the list of stopwords file
	 */
	function Filtering($stopwordsFile) {
		// using the global variable instead of local
		global $words;

		// open stopword file
		$file = file_get_contents($stopwordsFile, FILE_USE_INCLUDE_PATH);

		// put stopword into an array
		$stopwords = explode(',', $file);

		// filtering
		$words = array_diff($words, $stopwords);
	}

	/**
	 * A function to stemming a word into a basic word
	 */
	function Stemming() {
		// using the global variable instead of local
		global $words;

		// include composer autoloader
		require_once __DIR__ . '/vendor/autoload.php';

		// create stemmer
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer  = $stemmerFactory->createStemmer();

		// stemming
		foreach ($words as $word) {
			$output[] = $stemmer->stem($word);
		}

		// update the bag of words with the stemmed words
		$words = $output;
	}

	/**
	 * Constant
	 */
	$csvFile = 'source\output.csv'; // path to the csv file 
	$stopwordsFile = 'source\stopwords.txt'; // path to the stopwords file

	/**
	 * Variable
	 */
	$words = []; // a bag of words

	/**
	 * Where the main program begins
	 */

	// open csv file and store in an array
	$array = OpenCsv($csvFile);

	// tokenizing all message in array
	foreach ($array as $line) {
		Tokenizing($line[5]); // get only the messages
	}

	// filtering all words
	Filtering($stopwordsFile);

	// stemming all words
	Stemming();

	// show all preprocessed word
	print_r($words);

	// group and count same word in array
	$vals = array_count_values($words);
	echo '<br><br>Total of group: '.count($vals).'<br><br>';

	// descending sort
	asort($vals);
	$desc_vals = array_reverse($vals);
	print_r($desc_vals);
?>
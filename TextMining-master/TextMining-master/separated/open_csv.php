<?php
	$file = fopen('output.csv','r');

	while(! feof($file))
	{
		$array[] = fgetcsv($file);
	}

	fclose($file);

	foreach ($array as $line) {
		// print_r($line[5]);
		$messages[] = $line[5];
	}

	foreach ($messages as $txt) {
		print_r($txt);
	}
?>
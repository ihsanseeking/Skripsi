<?php

	$path_to_file = 'id.stopwords.02.01.2016.txt';
	$file_contents = file_get_contents($path_to_file);
	$file_contents = str_replace("\n",",",$file_contents);
	file_put_contents($path_to_file,$file_contents);

?>
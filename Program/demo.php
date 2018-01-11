<?php
// demo.php

// include composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container

$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
/*
$stemmer  = $stemmerFactory->createStemmer();

// stem
$sentence = 'Perekonomian Indonesia sedang dalam pertumbuhan yang membanggakan';
$output   = $stemmer->stem($sentence);

echo $output . "\n";
// ekonomi indonesia sedang dalam tumbuh yang bangga

echo $stemmer->stem('Mereka meniru-nirukannya') . "\n";
// mereka tiru

$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
*/
$dictionary = $stemmerFactory->createDefaultDictionary();
$dictionary->addWordsFromTextFile(__DIR__.'/my-dictionary.txt');
$dictionary->add('internet');
$dictionary->remove('desa');

$stemmer = new \Sastrawi\Stemmer\Stemmer($dictionary);

var_dump($stemmer->stem('internetan')); //internet

?>
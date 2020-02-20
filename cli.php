#!/usr/bin/env php
<?php

require "vendor/autoload.php";
require "src/Calculator.php";

// check if argument passed
if ($argc < 3) {
    fwrite(STDERR, "Expected 2 argument\n");
    exit -1;
}

// check if file exists
$input = $argv[1];
$output = $argv[2];
if (!file_exists($input)) {
    fwrite(STDERR, "File {$input} not found\n");
    exit -1;
}

// load-transform-store
$scanning = Scanning::load($input);
$result = calculate($scanning->booksToScores, $scanning->libraries, $scanning->days);
Scanning::save($result, $output);

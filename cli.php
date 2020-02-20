#!/usr/bin/env php
<?php

require "verndor/autoload.php";

// check if argument passed
if ($argc < 2) {
    fwrite(STDERR, "Expected 1 argument\n");
    exit -1;
}

// check if file exists
$input = $argv[1];
if (!file_exists($input)) {
    fwrite(STDERR, "File {$input} not found\n");
    exit -1;
}

// load input file
$scanning = Scanning::load($input);


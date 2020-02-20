<?php

class Scanning {

    public $days = 0;

    public $booksToScores = [];
    public $libraries = [];

    const DELIMITER = " ";

    public function __construct(integer $days, array $booksToScores, array $libraries) {
        $this->days = $days;
        $this->booksToScored = $booksToScores;
        $this->libraries = $libraries;
    }

    public static function load(string $file): Scanning {
        // init
        $h = fopen($file, "r");

        // load books count, libraries count, days
        list($booksCount, $librariesCount, $days) = fscanf($h, "%d %d %d");

        // load scores
        $booksToScores = fgetcsv($h, 0, self::DELIMITER);

        // load libraries
        $libraries = [];
        while (!feof($h)) {
            list($booksInLibraryCount, $onboardingTime, $booksPerDay) = fgetcsv($h, "%d %d %d");
            $booksInLibrary = fgetcsv($h, 0, self::DELIMITER);
            if ($booksInLibraryCount !== count($booksInLibrary)) {
                throw new Exception("Reading library expected to get {$booksInLibraryCount} books but read {count($booksInLibrary)} items");
            }
            $libraries[] = new Library($booksInLibrary, $onboardingTime, $booksPerDay);
        }

        return new Scanning($days, $booksToScores, $libraries);
    }

}
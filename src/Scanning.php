<?php

class Scanning {

    public $days = 0;
    public $booksToScores = [];
    public $libraries = [];

    const DELIMITER = " ";

    public function __construct(int $days, array $booksToScores, array $libraries) {
        $this->days = $days;
        $this->booksToScores = $booksToScores;
        $this->libraries = $libraries;
    }

    public static function load(string $file): Scanning {
        // init
        $h = fopen($file, "r");

        // load books count, libraries count, days
        list($booksCount, $librariesCount, $days) = fscanf($h, "%d %d %d");
        echo "Got $booksCount books in $librariesCount libraries, $days days to go\n";

        // load scores
        $booksToScores = fgetcsv($h, 0, self::DELIMITER);
        echo "Book scores: " . implode(", ", $booksToScores) . "\n";

        // load libraries
        $libraries = [];
        while (!feof($h)) {
            // read library properties
            list($booksInLibraryCount, $onboardingTime, $booksPerDay) = fscanf($h, "%d %d %d");
            if (!$booksInLibraryCount or !$onboardingTime or !$booksPerDay) break;
            echo "Read $booksInLibraryCount books in library, onboarding time is $onboardingTime, with $booksPerDay books per day processing capacity\n";

            // read books in library
            $booksInLibrary = fgetcsv($h, 0, self::DELIMITER);
            echo "Book ids: " . implode(", ", $booksInLibrary) . "\n";
            if (!$booksInLibrary) break;
            if ($booksInLibraryCount !== count($booksInLibrary)) {
                throw new Exception("Reading library expected to get {$booksInLibraryCount} books but read {count($booksInLibrary)} items");
            }
            $libraries[] = new Library($booksInLibrary, $onboardingTime, $booksPerDay);
        }

        return new Scanning($days, $booksToScores, $libraries);
    }

    public static function save(array $libraryToBooksQueue, string $file) {

        $output = count($libraryToBooksQueue) . " \n";

        foreach ($libraryToBooksQueue as $libraryId => $bookIds) {
            $output .= $libraryId . " " . count($bookIds) . "\n";
            $output .= implode(" ", $bookIds) . "\n";
        }

        file_put_contents($file, $output);
    }

}
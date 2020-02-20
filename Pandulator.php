<?php

class Library {
    public $onboardingTime = 0;
    public $bookIds = [];
    public $booksPerDay = 0;

    function __construct(int $onboardingTime, array $bookIds, int $booksPerDay) {
        $this->onboardingTime = $onboardingTime;
        $this->bookIds = $bookIds;
        $this->booksPerDay = $booksPerDay;
    }
}

$booksToScores = [
    "0" => 1,
    "1" => 2,
    "2" => 3,
    "3" => 4,
    "4" => 5,
    "5" => 6
];

$lib1 = new Library(2, [0, 1, 2], 2);
$lib2 = new Library(10, [2, 3, 4], 1);
$libraries = [$lib1, $lib2];
$libraryScores = [];
$booksToLibraries = [];
foreach ($libraries as $key => $library) {
    // count library score
    $sumScores = 0;
    foreach($library->bookIds as $id) {
        $sumScores += $booksToScores[$id];
    }
    $libraryScores[$key] = $sumScores / ($library->onboardingTime + count($library->bookIds) / $library->booksPerDay);

    // get books into array
    foreach($library->bookIds as $bookId) {
        if (empty($booksToLibraries[$bookId]))
            $booksToLibraries[$bookId] = [$key];
        else
            $booksToLibraries[$bookId][] = $key;
    }
}

arsort($libraryScores);
$librariesToBooks = [];
foreach($booksToLibraries as $bookId => $libraryArray) {
    echo "Book $bookId\n";
    foreach($libraryScores as $libId => $libScore) {
        if (in_array($libId, $libraryArray)) {
            echo "Adding $bookId to $libId\n";
            if (empty($librariesToBooks[$libId])) {
                $librariesToBooks[$libId] = [$bookId];
            } else {
                $librariesToBooks[$libId][] = $bookId;
            }
            break;
        }
    }
}

$librariesToFinalScore = [];
foreach ($librariesToBooks as $libId => $bookIds) {
    $sumScores = 0;
    foreach($bookIds as $id) {
        $sumScores += $booksToScores[$id];
    }
    echo "Lib id $libId\n";
    $librariesToFinalScore[$libId] = $sumScores / ($libraries[$libId]->onboardingTime + count($bookIds) / $libraries[$libId]->booksPerDay);

}

$librariesSortedForQueue = array_keys(arsort($librariesToFinalScore));
$libraryQueue = [];
foreach ($librariesSortedForQueue as $libId) {
    $libraryQueue[$libId] = $librariesToBooks[$libId];
}


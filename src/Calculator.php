<?php

function calculate(array $booksToScores, array $libraries, int $days) {

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
arsort($librariesToFinalScore);
$librariesSortedForQueue = array_keys($librariesToFinalScore);
$libraryQueue = [];
foreach ($librariesSortedForQueue as $libId) {
    $libraryQueue[$libId] = $librariesToBooks[$libId];
}

$sumOnboarding = 0;
foreach($libraryQueue as $libId => $books) {
    $libraries[$libId]->onboardingTime += $sumOnboarding;
    $sumOnboarding = $libraries[$libId]->onboardingTime;
    echo "Sum onboarding $sumOnboarding";
}

$booksScanned = [];
foreach ($libraryQueue as $libId => $books) {
    echo " Lib id: $libId\n";
    echo "\n";
    if ($libraries[$libId]->onboardingTime < $days) {
        $booksLeft = ($days - $libraries[$libId]->onboardingTime) * $libraries[$libId]->booksPerDay;
        $numBooksScanned = count($books) < $booksLeft ? count($books) : $booksLeft;
        for ($i = 0; $i < $numBooksScanned; $i++) {
            $booksScanned[$libId][] = $books[$i];
        }
    }
}

return $booksScanned;

}


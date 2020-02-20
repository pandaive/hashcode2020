<?php

class Library {

    public $bookIds = [];
    public $onboardingTime = 0;
    public $booksPerDay = 0;

    public function __construct(array $booksIds, int $onboardingTime, int $booksPerDay) {
        $this->bookIds = $bookIds;
        $this->onboardingTime = $onboardingTime;
        $this->booksPerDay = $booksPerDay;
    }
}
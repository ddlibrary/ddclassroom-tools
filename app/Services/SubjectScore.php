<?php

namespace App\Services;

class SubjectScore
{
    private $midtermScoreConfig = [
        'activity' => 5,
        'homework' => 5,
        'evaluation' => 5,
        'attendance' => 5,
        'written' => 10,
        'oral' => 10,
        'total' => 40,
    ];

    private $finalScoreConfig = [
        'activity' => 5,
        'homework' => 5,
        'evaluation' => 10,
        'attendance' => 0,
        'written' => 20,
        'oral' => 10,
        'total' => 60,
    ];

    private $type;
    private $scoreConfig;

    public function __construct(int $type)
    {
        $this->type = $type;
        $this->setScoreConfig();
    }

    private function setScoreConfig(): void
    {
        $this->scoreConfig = $this->type == 1 ? $this->midtermScoreConfig : $this->finalScoreConfig;
    }

    public function getAll(): array
    {
        return $this->scoreConfig;
    }

    public function getOral(): int
    {
        return $this->scoreConfig['oral'];
    }

    public function getWritten(): int
    {
        return $this->scoreConfig['written'];
    }

    public function getAttendance(): int
    {
        return $this->scoreConfig['attendance'];
    }

    public function getEvaluation(): int
    {
        return $this->scoreConfig['evaluation'];
    }

    public function getHomework(): int
    {
        return $this->scoreConfig['homework'];
    }

    public function getActivity(): int
    {
        return $this->scoreConfig['activity'];
    }

    public function getTotal(): int
    {
        return $this->scoreConfig['total'];
    }
}

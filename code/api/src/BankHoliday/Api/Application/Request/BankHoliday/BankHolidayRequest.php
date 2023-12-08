<?php

namespace Acme\Services\BankHoliday\Api\Application\Request\BankHoliday;

class BankHolidayRequest
{
    private string $location;
    private ?int $year;

    public function __construct(string $location, int $year = null)
    {
        $this->location = $location;
        $this->year = $year ?: DATE('Y');
    }

    public function location(): string
    {
        return $this->location;
    }

    public function year(): ?int
    {
        return $this->year;
    }
}

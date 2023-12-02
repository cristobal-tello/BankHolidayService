<?php

namespace Acme\Services\BankHoliday\Api\Application\Request\BankHoliday;

class BankHolidayRequest
{
    private string $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function location(): string
    {
        return $this->location;
    }
}

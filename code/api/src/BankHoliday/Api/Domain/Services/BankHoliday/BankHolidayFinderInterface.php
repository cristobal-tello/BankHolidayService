<?php

namespace Acme\Services\BankHoliday\Api\Domain\Services\BankHoliday;

interface BankHolidayFinderInterface
{
    public function getBankHolidays(string $location, ?int $year): array;
}

<?php

namespace Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday;

interface BankHolidayRepositoryInterface
{ 
    public function getHolidays() : array;
}
<?php

namespace Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;

interface BankHolidayRepositoryInterface
{ 
    public function getLocationHolidays(Location $location) : array;
    public function saveHolidays(Location $location, array $bankHolidays);
}
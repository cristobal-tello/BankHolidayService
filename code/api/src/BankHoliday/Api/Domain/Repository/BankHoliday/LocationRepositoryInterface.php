<?php

namespace Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;

interface LocationRepositoryInterface
{
    public function getLocationHolidays(string $locationName, int $year): ?Location;
    public function saveLocationHolidays(Location $location);
}

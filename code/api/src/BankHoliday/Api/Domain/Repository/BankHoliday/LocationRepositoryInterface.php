<?php

namespace Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;

interface LocationRepositoryInterface
{
    public function getLocation(string $locationName): ?Location;
    public function saveLocation(Location $location);
}

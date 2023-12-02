<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\BankHoliday;
use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;
use Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday\BankHolidayRepositoryInterface;
use DateTime;

class BankHolidayMockRepository implements BankHolidayRepositoryInterface
{
    public function getHolidays() : array
    { 
        $locationTarragona = new Location(1, "Tarragona");
        $locationReus = new Location(2, "Reus");
        $locationBarcelona = new Location(3, "Barcelona");

        return
        [
            new BankHoliday(1, $locationTarragona, new DateTime('2023-09-23'), "Santa Tecla"),
            new BankHoliday(2, $locationReus, new DateTime('2023-09-25'), "Santa Misericordia"),
            new BankHoliday(3, $locationBarcelona, new DateTime('2023-09-24'), "Santa Merce"),

            new BankHoliday(4, $locationTarragona, new DateTime('2023-08-19'), "Sant Magi"),
            new BankHoliday(5, $locationReus, new DateTime('2023-07-30'), "Sant Pere")
        ];
    }
}
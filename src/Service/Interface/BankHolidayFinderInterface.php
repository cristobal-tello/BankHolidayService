<?php

namespace App\Service\Interface;

use App\Entity\Country;
use App\Entity\Location;
use Doctrine\Common\Collections\ArrayCollection;

interface BankHolidayFinderInterface
{
    // Return a list of bank holidays for a given country, location and year
    public function getBankHolidays(Country $country, Location $location, int $year): ArrayCollection;
}
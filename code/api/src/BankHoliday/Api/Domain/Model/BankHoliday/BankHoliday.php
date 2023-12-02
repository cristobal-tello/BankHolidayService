<?php

namespace Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;
use DateTime;

class BankHoliday
{
    private int $id;
    private Location $location;
    private DateTime $date;
    private string $name;

    public function __construct(int $id, Location $location, DateTime $date, string $name)
    {
        $this->id = $id;
        $this->location = $location;
        $this->date = $date;
        $this->name = $name;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function date(): DateTime
    {
        return $this->date;
    }

    public function location(): Location
    {
        return $this->location;
    }

    public function name(): string
    {
        return $this->name;
    }
}

<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity;

use DateTimeInterface;

class BankHoliday
{
    private string $id;
    private Location $location;
    private string $name;
    private DateTimeInterface $date;

    public function __construct(string $id, Location $location, DateTimeInterface $date, string $name)
    {
        $this->id = $id;
        $this->location = $location;
        $this->date = $date;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function date(): DateTimeInterface
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

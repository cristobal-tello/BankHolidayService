<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity;

use DateTimeInterface;
use Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity\Location as LocationEntity;

class BankHoliday
{
    private string $id;
    private LocationEntity $location;
    private string $name;
    private DateTimeInterface $date;

    public function __construct(string $id, LocationEntity $location, DateTimeInterface $date, string $name)
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

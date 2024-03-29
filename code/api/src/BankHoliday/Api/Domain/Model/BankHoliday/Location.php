<?php

namespace Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday;

class Location
{
    private string $id;
    private string $name;
    private \ArrayObject $holidays;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->holidays = new \ArrayObject();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function holidays(): \ArrayObject
    {
        return $this->holidays;
    }

    public function setHolidays(array $holidays)
    {
        $this->holidays = $holidays;
    }
}

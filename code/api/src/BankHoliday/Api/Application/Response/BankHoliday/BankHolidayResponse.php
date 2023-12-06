<?php

namespace Acme\Services\BankHoliday\Api\Application\Response\BankHoliday;

class BankHolidayResponse
{
    private string $name;
    private string $date;

    public function __construct(\DateTime $date, string $name)
    {
        $this->date = $date->format('Y-m-d');
        $this->name = $name;
    }

    public function date(): string
    {
        return $this->date;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}

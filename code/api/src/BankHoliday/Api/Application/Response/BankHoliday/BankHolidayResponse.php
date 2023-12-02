<?php

namespace Acme\Services\BankHoliday\Api\Application\Response\BankHoliday;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\BankHoliday;

class BankHolidayResponse
{
    private string $id;
    private string $location;
    private string $name;
    private string $date;

    public function __construct(BankHoliday $bankHoliday)
    {
        $this->id = $bankHoliday->id();
        $this->location = $bankHoliday->location()->name();
        $this->name = $bankHoliday->name();
        $this->date = $bankHoliday->date()->format('Y-m-d');
    }

    public function id(): string
    {
        return $this->id;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function date(): string
    {
        return $this->date;
    }

    public function name(): string
    {
        return $this->location;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}

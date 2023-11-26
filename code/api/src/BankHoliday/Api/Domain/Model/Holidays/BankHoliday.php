<?php

namespace Acme\Services\BankHoliday\Api\Domain\Model\Holidays;

class BankHoliday
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;

    }

    public function id(): int
    {
        return $this->id;
    }


    public function name(): string
    {
        return $this->name;
    }


}

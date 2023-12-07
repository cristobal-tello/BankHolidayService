<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Location
{
    private string $id;
    private string $name;
    private Collection $bankHolidays;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->bankHolidays = new ArrayCollection();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function holidays(): Collection
    {
        return $this->bankHolidays;
    }
}

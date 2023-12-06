<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\BankHoliday;
use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;
use Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity\BankHoliday as BankHolidayEntity;
use Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday\BankHolidayRepositoryInterface;

class BankHolidayDoctrineRepository extends DoctrineRepository implements BankHolidayRepositoryInterface
{
    public function getLocationHolidays(Location $location): array
    {
        $bankHolidayArray = [];
        $bankHolidayEntities = $this->repository->findAll();
        foreach ($bankHolidayEntities as $bankHolidayEntity) {
            $bankHolidayArray[] = new BankHoliday(
                $bankHolidayEntity->id(),
                $bankHolidayEntity->location(),
                $bankHolidayEntity->date(),
                $bankHolidayEntity->name()
            );
        }
        return $bankHolidayArray;
    }

    public function saveHolidays(Location $location, array $bankHolidays)
    {
        return [];
    }

    protected function entityClassName(): string
    {
        return BankHolidayEntity::class;
    }
}

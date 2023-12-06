<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\BankHoliday;
use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;
use Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity\Location as LocationEntity;
use Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity\BankHoliday as BankHolidayEntity;
use Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday\LocationRepositoryInterface;

class LocationDoctrineRepository extends DoctrineRepository implements LocationRepositoryInterface
{
    public function getLocation(string $location): ?Location
    {
        $locationEntity = $this->repository->findOneBy(['name' => $location]);

        if ($locationEntity !== null) {
            $location = new Location($locationEntity->id(), $locationEntity->name());
            foreach ($locationEntity->holidays() as $bankHolidayEntity) {
                $location->holidays()[] = new BankHoliday(
                    $bankHolidayEntity->id(),
                    $location,
                    $bankHolidayEntity->date(),
                    $bankHolidayEntity->name()
                );
            }

            return $location;
        }

        return null;
    }

    public function saveLocation(Location $location)
    {
        $locationEntity = new LocationEntity(
            $location->id(),
            $location->name()
        );

        foreach ($location->holidays() as $bankHoliday) {
            $locationEntity->holidays()->add(
                new BankHolidayEntity(
                    $bankHoliday->id(),
                    $locationEntity,
                    $bankHoliday->date(),
                    $bankHoliday->name()
                )
            );
        }

        $this->entityManager->persist($locationEntity);
        $this->entityManager->flush();
    }

    protected function entityClassName(): string
    {
        return LocationEntity::class;
    }
}

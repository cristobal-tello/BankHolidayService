<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine;

use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\BankHoliday;
use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;
use Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity\Location as LocationEntity;
use Acme\Services\BankHoliday\Api\Infrastructure\Database\BankHoliday\Doctrine\Entity\BankHoliday as BankHolidayEntity;
use Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday\LocationRepositoryInterface;

class LocationDoctrineRepository extends DoctrineRepository implements LocationRepositoryInterface
{
    public function getLocationHolidays(string $locationName, int $year): ?Location
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('l', 'bh')
            ->from($this->entityClassName(), 'l')
            ->leftJoin('l.bankHolidays', 'bh', 'WITH', 'YEAR(bh.date) = :year')
            ->setParameter('year', $year)
            ->where('l.name = :location')
            ->setParameter('location', $locationName)
            ->getQuery();

        $locationEntity = $queryBuilder->getOneOrNullResult();

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

    public function saveLocationHolidays(Location $location)
    {
        $locationEntity = $this->repository->findOneBy(['name' => $location->name()]) ?: new LocationEntity(
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

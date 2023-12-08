<?php

namespace Acme\Services\BankHoliday\Api\Application\Query\BankHoliday;

use Acme\Services\BankHoliday\Api\Application\Request\BankHoliday\BankHolidayRequest;
use Acme\Services\BankHoliday\Api\Application\Response\BankHoliday\BankHolidayResponse;
use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\BankHoliday;
use Acme\Services\BankHoliday\Api\Domain\Model\BankHoliday\Location;
use Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday\LocationRepositoryInterface;
use Acme\Services\BankHoliday\Api\Domain\Services\BankHoliday\BankHolidayFinderInterface;
use Ramsey\Uuid\Uuid;

class BankHolidayHandler
{
    private LocationRepositoryInterface $locationRepository;
    private BankHolidayFinderInterface $bankHolidayFinder;

    public function __construct(LocationRepositoryInterface $locationRepository, BankHolidayFinderInterface $bankHolidayFinder)
    {
        $this->locationRepository = $locationRepository;
        $this->bankHolidayFinder = $bankHolidayFinder;
    }

    public function __invoke(BankHolidayRequest $bankHolidayRequest): array
    {
        // Check if we can get the location from the database
        $location = $this->locationRepository->getLocationHolidays($bankHolidayRequest->location(), $bankHolidayRequest->year());

        if (!$location || 0 == count($location->holidays())) {
            $bankHolidaysFound = $this->bankHolidayFinder->getBankHolidays($bankHolidayRequest->location(), $bankHolidayRequest->year());
            
            // TO-DO: Exception here if bankHolidayFound is empty? 

            $location = $location ?: new Location(
                Uuid::uuid4()->toString(),
                $bankHolidayRequest->location()
            );
               
            foreach ($bankHolidaysFound as $bankHoliday) {
                $location->holidays()[] =
                    new BankHoliday(
                        Uuid::uuid4()->toString(),
                        $location,
                        $bankHoliday['date'],
                        $bankHoliday['name']
                    );
            }
            $this->locationRepository->saveLocationHolidays($location);
        }

        $bankHolidayResponseArray = [];
        foreach ($location->holidays() as $bankHoliday) {
            $bankHolidayResponse = new BankHolidayResponse(
                $bankHoliday->date(),
                $bankHoliday->name()
            );
            $bankHolidayResponseArray[] = $bankHolidayResponse->toArray();
        }

        return $bankHolidayResponseArray;
    }
}

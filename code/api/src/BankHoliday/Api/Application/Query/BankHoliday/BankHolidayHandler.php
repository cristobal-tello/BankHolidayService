<?php

namespace Acme\Services\BankHoliday\Api\Application\Query\BankHoliday;

use Acme\Services\BankHoliday\Api\Application\Response\BankHoliday\BankHolidayResponse;
use Acme\Services\BankHoliday\Api\Domain\Repository\BankHoliday\BankHolidayRepositoryInterface;


class BankHolidayHandler
{
    private BankHolidayRepositoryInterface $bankHolidayRepository;

    public function __construct(BankHolidayRepositoryInterface $bankHolidayRepository)
    {
        $this->bankHolidayRepository = $bankHolidayRepository;
    }

    public function __invoke(): array
    {
        $bankHolidayResponseArray = [];
        foreach ($this->bankHolidayRepository->getHolidays() as $bankHoliday) {
            $bankHolidayResponse = new BankHolidayResponse($bankHoliday);
            $bankHolidayResponseArray[] = $bankHolidayResponse->toArray();
        }
        return $bankHolidayResponseArray;
    }
}

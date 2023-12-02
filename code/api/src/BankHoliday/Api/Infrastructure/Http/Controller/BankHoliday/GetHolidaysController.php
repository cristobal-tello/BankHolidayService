<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Http\Controller\BankHoliday;

use Acme\Services\BankHoliday\Api\Application\Query\BankHoliday\BankHolidayHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetHolidaysController
{
    private BankHolidayHandler $bankHolidayHandler;

    public function __construct(BankHolidayHandler $bankHolidayHandler)
    {
        $this->bankHolidayHandler = $bankHolidayHandler;
    }

    public function __invoke()
    {
        try {
            $bankHolidays = ($this->bankHolidayHandler)();

            $response = new JsonResponse(
                [
                    'status' => 'ok',
                    'result' => $bankHolidays
                ]
            );
        } catch (\Throwable $exception) {
            $response = new JsonResponse(
                [
                    'status' => 'error',
                    'errorMessage' => $exception->getMessage()
                ],
                500
            );
        }

        return $response;
    }
}

<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Http\Controller\BankHoliday;

use Acme\Services\BankHoliday\Api\Application\Query\BankHoliday\BankHolidayHandler;
use Acme\Services\BankHoliday\Api\Application\Request\BankHoliday\BankHolidayRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetHolidaysController
{
    private BankHolidayHandler $bankHolidayHandler;

    public function __construct(BankHolidayHandler $bankHolidayHandler)
    {
        $this->bankHolidayHandler = $bankHolidayHandler;
    }

    public function __invoke(Request $request)
    {
        try {
            /*$bankHolidayRequest = new BankHolidayRequest(
                $request->get('location'),
                $request->get('year')
            );*/

            $bankHolidayRequest = new BankHolidayRequest(
                'Tarragona', 
                2023
            );
            
            $bankHolidays = ($this->bankHolidayHandler)($bankHolidayRequest);

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

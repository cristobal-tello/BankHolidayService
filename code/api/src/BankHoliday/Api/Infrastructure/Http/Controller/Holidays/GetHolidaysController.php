<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Http\Controller\Holidays;

use Acme\Services\BankHoliday\Api\Domain\Model\Holidays\BankHoliday;
use Acme\Services\BankHoliday\Api\Domain\Model\Holidays\Location;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetHolidaysController
{
    public function __invoke()
    {
        // Just to test it
        $bankHoliday = new BankHoliday(
            1,
            "Santa Tecla!!",
        );


        return new JsonResponse(
            [
                'status' => 'ok!!!',
                'name' => $bankHoliday->name()
            ]
        );
    }
}

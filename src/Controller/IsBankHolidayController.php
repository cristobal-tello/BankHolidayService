<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Location;
use App\Service\CalendarioLaboralBankHolidayFinder;
use App\Service\Interface\BankHolidayFinderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

// Test :
// http://127.0.0.1:8000/isbankholiday/esp/tarragona/2023-09-23
// http://127.0.0.1:8000/isbankholiday/esp/madrid/2023-11-01
// http://127.0.0.1:8000/isbankholiday/esp/vinyols%20i%20els%20arcs/2023-12-25
// http://127.0.0.1:8000/isbankholiday/esp/vilanova%20i%20la%20geltru/2023-12-25

class IsBankHolidayController extends AbstractController
{
    // The service that will find the bank holidays
    private BankHolidayFinderInterface $bankHolidayFinderService;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, CalendarioLaboralBankHolidayFinder $bankHolidayFinderService)
    {
        $this->bankHolidayFinderService = $bankHolidayFinderService;
        $this->logger = $logger;
    }

    // This controller will return a JSON response with the bank holidays for a given country, location and date
    #[Route('/isbankholiday/{isoCountryCode}/{locationName}/{date}', name: 'isbankholiday')]
    public function index(EntityManagerInterface $entityManager, string $isoCountryCode, string $locationName, string $date): JsonResponse
    {
        $result = [];
        $this->logger->info("Request received: $isoCountryCode, $locationName, $date");
        try {
            // Convert the date to DateTime object
            $dateTime = new \DateTime($date);

            // Get the country from the database
            $country = $entityManager->getRepository(Country::class)->findOneBy(['iso_code' => $isoCountryCode]);

            // Throw an exception if the country is not found
            if (!$country) {
                throw new \Exception("Cannot found a country with the country code iso {$isoCountryCode}");
            }

            // Decode the location name and capitalize the first letter
            $locationName = ucfirst(urldecode($locationName));

            // Check if we can get the location from the database
            $location = $entityManager->getRepository(Location::class)->findOneBy(
                ['name' => $locationName, 'country' => $country]);

            // If the location is not found, try to get the location from BankHolidayFinderInterface
            if (!$location) {
                $location = new Location($country, $locationName);
                $holidays = $this->bankHolidayFinderService->getBankHolidays($country, $location, $dateTime->format('Y'));

                // Persist the holidays before persist the location
                foreach ($holidays as $holiday) {
                    $entityManager->persist($holiday);
                }

                // Set the holidays to the location
                $location->setHolidays($holidays);

                // Persist the location to be able to get the holidays from the database later
                $entityManager->persist($location);
                $entityManager->flush();
            }

            // Get the holidays from location
            $holiday = current(array_filter($location->getHolidays()->toArray(), function ($holiday) use ($date) {
                return $holiday->getDate()->format('Y-m-d') === $date;
            }));

            // Prepare the result
            if ($holiday) {
                $result = ['result' => 'true', 'date' => $holiday->getDate()->format('Y-m-d'), 'name' => $holiday->getName()];
            } else {
                $result = ['result' => 'false'];
            }
        } catch (\Throwable $t) {
            $result = ['result' => 'error', 'message' => $t->getMessage()];
        } finally {
            $this->logger->info("Response: " . json_encode($result));
            return $this->json($result);
        }
    }
}

<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\Holiday;
use App\Entity\Location;
use App\Service\Interface\BankHolidayFinderInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DomCrawler\Crawler;

/*
This class implements the BankHolidayFinderInterface to get bank holidays from https://www.calendario-laboral.com
*/

class CalendarioLaboralBankHolidayFinder implements BankHolidayFinderInterface
{
    /* Return a list of bank holidays for a given country, location and year
    * @param Country $country Country to get bank holidays
    * @param Location $location Location to get bank holidays
    * @param int $year Year to get bank holidays
    * @return ArrayCollection List of bank holidays
    */
    function getBankHolidays(Country $country, Location $location, int $year): ArrayCollection
    {
        $results = new ArrayCollection();

        // Get the page content from the website and parse it
        // The page is like https://www.calendario-laboral.com/calendario-laboral-madrid-2021
        $locationName = str_replace(" ", "-", $location->getName());
        $page = file_get_contents("https://www.calendario-laboral.com/calendario-laboral-$locationName-$year");
        $crawler = new Crawler($page);

        // Get the holidays from the page
        $crawler->filter('.festives-list__item')->each(function (Crawler $node) use ($country, $location, $year, $results) {
            $split_result = explode(",", $node->text());

            // Add the holiday to the results
            $results->add(new Holiday(
                $location,
                $this->parseDate(trim($split_result[1]), $year, $country->getLocale()),
                $this->removeLastDayOfTheWeek($split_result[0], $country->getLocale())));
        });

        return $results;
    }

    /* Parse a date string like "1 de enero" to a DateTime object
    * @param string $date Date string to parse
    * @param string $currentYear Current year
    * @return DateTime Date parsed
    */
    private function parseDate(string $date, int $currentYear, $locale): DateTime
    {
        // The date is like "1 de enero"
        $splitDate = explode("de", $date);
        // Get the numeric month
        $numMonth = $this->getNumericMonth(trim($splitDate[1]), $locale);
        // Format the date to "20210101"
        $dateFormatted = $currentYear . $numMonth . str_pad(trim($splitDate[0]), 2, '0', STR_PAD_LEFT);
        // Create the DateTime object
        $dateTime = DateTime::createFromFormat("Ymd", $dateFormatted);
        $dateTime->setTime(0, 0, 0);
        // Return the DateTime object
        return $dateTime;
    }

    /* Get the numeric month from a month name
     * @param string $monthToFind Month name to find
     * @param string $locale Locale to use
     * @return string Numeric month padded with 0
     */
    private function getNumericMonth(string $monthToFind, string $locale): string
    {
        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $date = new DateTime('2000-' . $month . '-01');
            $monthFormatter = new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'MMMM');
            $months[$monthFormatter->format($date)] = ['numericMonth' => $month];
        }
        $numericMonth = $months[strtolower($monthToFind)]['numericMonth'];
        return str_pad($numericMonth, 2, '0', STR_PAD_LEFT);
    }

    /*
     * Remove the last day of the week from a string
     * @param string $name Name to remove the last day of the week
     * @param string $locale Locale to use
     * @return string Name without the last day of the week in the name
     */
    private function removeLastDayOfTheWeek(string $name, string $locale): string
    {
        // Get a list of days of the week according to the locale
        for ($days = 1; $days <= 7; $days++) {
            // 2001-01-01 was a Monday
            $date = new DateTime('2001-01-' . str_pad($days, 2, '0', STR_PAD_LEFT));
            $dayFormatter = new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'EEEE');
            $daysOfTheWeek[] = ucfirst($dayFormatter->format($date));
        }

        // Split the name by spaces
        $splitName = explode(" ", $name);

        // Reverse loop the array and remove the first day of the week found
        for ($i = count($splitName) - 1; $i >= 0; $i--) {
            // If the word is in the days of the week array, remove it
            if (in_array($splitName[$i], $daysOfTheWeek)) {
                $name = str_replace($splitName[$i], "", $name);
                break;
            }
        }

        return trim($name);
    }
}
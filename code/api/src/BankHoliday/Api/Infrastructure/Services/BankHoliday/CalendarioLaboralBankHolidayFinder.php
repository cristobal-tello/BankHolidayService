<?php

namespace Acme\Services\BankHoliday\Api\Infrastructure\Services\BankHoliday;

use Acme\Services\BankHoliday\Api\Domain\Services\BankHoliday\BankHolidayFinderInterface;
use ArrayObject;
use Symfony\Component\DomCrawler\Crawler;
use DateTime;

/*
This class implements the BankHolidayFinderInterface to get bank holidays from https://www.calendario-laboral.com
*/

class CalendarioLaboralBankHolidayFinder implements BankHolidayFinderInterface
{
    const JSON_DATA_START = "var JSONData = ";
    const JSON_DATA_END = "var data = JSONData.replaceAll";

    /* Return a list of bank holidays for a given location and year
    * @param string $location Location to get bank holidays
    * @param int $year Year to get bank holidays
    * @return Array List of bank holidays
    */
    function getBankHolidays(string $location, ?int $year): array
    {
        // We need an ArrayObject() and not a simple array because [] don't work when we use clousures
        $results = new ArrayObject();

        // Get the page content from the website and parse it
        // The page is like https://www.calendario-laboral.com/calendario-laboral-madrid-2021
        $locationToFind = strtolower(str_replace(" ", "-", $location));
        $yearToFind = $year ?: date('Y');
        $page = file_get_contents("https://www.calendario-laboral.com/calendario-laboral-$locationToFind-$yearToFind");

        // Crawl the page to try to get JSon with holidays
        $crawler = new Crawler($page);

        // Holidays are in a JSON javascript object, we get all javascript nodes
        $crawler->filter('script[type="text/javascript"]')->each(function (Crawler $node) use ($location, $results) {

            // Holidays are in a JSON, we try to get them
            if (strpos(substr($node->text(), 0, 25), self::JSON_DATA_START) !== false) {

                // Now that we get the node, we need to parse it
                $json = $this->parseNodeTextToJson($node->text());

                // Get the valid name of the location/city
                $validLocation = $json->city;

                // We have a valid json with the holidays info, loop to get all holidays
                foreach ($json->festives as $festiveDay) {
                    $festiveDateTime = new DateTime($festiveDay->endDate);
                    $festiveDateTime->setTime(0, 0, 0);
                    $results->append(
                        [
                            'location' => html_entity_decode($validLocation, ENT_QUOTES | ENT_COMPAT | ENT_HTML5 | ENT_XML1, 'UTF-8'),
                            'date' => $festiveDateTime,
                            'name' =>  html_entity_decode($this->my_numeric2character($festiveDay->descripcion), ENT_QUOTES | ENT_COMPAT | ENT_HTML5 | ENT_XML1, 'UTF-8'),
                        ]
                    );
                }
            }
        });

        // Return $results as array
        return  $results->getArrayCopy();
    }

    /* Custom function to parse an string convert into JSON object
    * @param string $nodeText Node text to be parsed
    * @return mixed JSON object
    */
    private function parseNodeTextToJson(string $nodeText)
    {
        $jsonString = trim($nodeText);
        $jsonString = str_replace(self::JSON_DATA_START, '', $jsonString);
        $jsonString = str_replace('"', '', $jsonString);
        $jsonString = str_replace('&quot;', '"', $jsonString);
        $jsonString = str_replace('$', '', $jsonString);
        $position = strpos($jsonString, self::JSON_DATA_END);
        $jsonString = substr($jsonString, 0, $position);
        $jsonString = str_replace(';', '', $jsonString);
        return json_decode($jsonString);
    }

    /* Converts any HTML-entities into characters */
    private function my_numeric2character($t)
    {
        $convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
        return mb_decode_numericentity($t, $convmap, 'UTF-8');
    }
}

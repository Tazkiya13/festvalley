<?php

namespace App\Helpers;

class Countries
{
    /**
     * Get all available countries
     */
    public static function getCountries()
    {
        return [
            'Germany' => 'Germany',
            'Netherlands' => 'Netherlands'
        ];
    }

    /**
     * Get all regions/states/provinces for all countries
     */
    public static function getAllRegions()
    {
        return [
            'Germany' => [
                'Baden-Württemberg',
                'Bayern (Bavaria)',
                'Berlin',
                'Brandenburg',
                'Bremen',
                'Hamburg',
                'Hessen (Hesse)',
                'Mecklenburg-Vorpommern',
                'Niedersachsen (Lower Saxony)',
                'Nordrhein-Westfalen (North Rhine-Westphalia)',
                'Rheinland-Pfalz (Rhineland-Palatinate)',
                'Saarland',
                'Sachsen (Saxony)',
                'Sachsen-Anhalt',
                'Schleswig-Holstein',
                'Thüringen (Thuringia)'
            ],
            'Netherlands' => [
                'Drenthe',
                'Flevoland',
                'Friesland (Fryslân)',
                'Gelderland',
                'Groningen',
                'Limburg',
                'Noord-Brabant',
                'Noord-Holland',
                'Overijssel',
                'Utrecht',
                'Zeeland',
                'Zuid-Holland'
            ]
        ];
    }

    /**
     * Get regions for a specific country
     */
    public static function getRegionsForCountry($country)
    {
        $regions = self::getAllRegions();
        return isset($regions[$country]) ? $regions[$country] : [];
    }

    /**
     * Check if a country exists
     */
    public static function isValidCountry($country)
    {
        return array_key_exists($country, self::getCountries());
    }

    /**
     * Check if a region exists for a given country
     */
    public static function isValidRegion($country, $region)
    {
        $regions = self::getRegionsForCountry($country);
        return in_array($region, $regions);
    }

    /**
     * Get country and region data as JSON for JavaScript
     */
    public static function getRegionsAsJson()
    {
        return json_encode(self::getAllRegions());
    }
}

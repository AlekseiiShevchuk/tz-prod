<?php

namespace App\Services;

use App\Models\Country;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class GeoIPService
{
    public static function getCountryIdForCurrentVisitor()
    {

        $reader = new Reader(app_path('Services/GeoLite2-Country.mmdb'));
        try {
            $countryRecord = $reader->country(request()->ip());
        } catch (AddressNotFoundException $exception) {
            return null;
        }

        if (!empty($countryRecord->country->isoCode)) {
            $country = Country::where('iso', $countryRecord->country->isoCode)->first();
            if (null != $country) {
                return $country->id;
            }
        }

    }
}
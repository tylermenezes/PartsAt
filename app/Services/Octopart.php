<?php

namespace PartsAt\Services;

class Octopart {
    const octopartBaseUri = 'https://octopart.com/api/v3/';
    protected static function getPartData($mpn)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => self::octopartBaseUri,
        ]);
        return json_decode($client->get('parts/match', ['query' => [
            'apikey' => config('octopart.apikey'),
            'queries' => json_encode([['mpn' => $mpn]])
        ]])->getBody());
    }

    public static function getPricing($mpn)
    {
        $fullResults = self::getPartData($mpn);
        if ($fullResults->results[0]->hits == 0) {
            return [];
        }

        $offers = $fullResults->results[0]->items[0]->offers;
        $breaks = [];

        foreach ($offers as $offer) {
            if (!isset($offer->prices->USD)) continue;
            foreach ($offer->prices->USD as $breakInfo) {
                $break = $breakInfo[0];
                $price = $breakInfo[1];
                if (!isset($breaks[$break])) {
                    $breaks[$break] = $price;
                }
            }
        }

        return $breaks;
    }
}

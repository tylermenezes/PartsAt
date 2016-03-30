<?php

namespace PartsAt\Services;

use PartsAt\Models;

class PartBag {
    public static function FindPart($bagLookup) {
        $partInfo = self::getResults($bagLookup);
        $existing = Models\Part::where('source_pn', '=', $partInfo->source_pn)->first();
        if ($existing) {
            return $existing;
        } else {
            $created = new Models\Part;
            $created->pn = $partInfo->pn;
            $created->source = $partInfo->source;
            $created->source_pn = $partInfo->source_pn;
            $created->manufacturer = $partInfo->manufacturer;
            $created->description = $partInfo->description;
            $created->save();
            return $created;
        }
    }

    protected static function getResults($bagLookup) {
        if (strlen($bagLookup) == 22) {
            return self::digikey(substr($bagLookup, 0, 7));
        } else {
            return self::mouser($bagLookup);
        }
    }

    protected static function mouser($bagLookup) {
        $client = new \SoapClient('http://www.mouser.com/service/searchapi.asmx?WSDL', [
            "exceptions" => 0, "trace" => 1,
            'stream_context' => stream_context_create(['http' => ['user_agent' => "User-Agent: PartsAt"]])
        ]);
        $client->__setSoapHeaders([
            new \SoapHeader(
                'http://api.mouser.com/service',
                'MouserHeader',
                [
                    'AccountInfo' => ['PartnerID' => config('mouser.api_key')]
                ])
        ]);
        $part = $client->SearchByPartNumber(['mouserPartNumber' => $bagLookup])->SearchByPartNumberResult->Parts->MouserPart;
        return (object)[
            'pn' => $part->ManufacturerPartNumber,
            'source' => 'mouser',
            'source_pn' => $part->MouserPartNumber,
            'manufacturer' => $part->Manufacturer,
            'description' => $part->Description,
        ];

    }

    protected static function digikey($bagLookup) {
        $client = new \SoapClient('https://services.digikey.com/Mobile/MobileV1.asmx?wsdl');
        $client->__setSoapHeaders([
            new \SoapHeader(
                'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
                'Security',
                [
                    'UsernameToken' => [
                        'Username' => 'iPhoneMobileApplication',
                        'Password' => 'yu78r5e3w2'
                    ]
                ]),
            new \SoapHeader(
                'http://services.digikey.com/MobileV1',
                'PartnerInformation',
                [
                    'PartnerID' => '{42E9A111-22AB-4E95-91AE-BC509F8F16F5}'
                ]),
            new \SoapHeader(
                'http://services.digikey.com/MobileV1',
                'CustomerNumber',
                0
            ),
            new \SoapHeader(
                'http://services.digikey.com/MobileV1',
                'Language',
                'en'
            ),
        ]);
        $part = $client->GetProductInfo(['partId' => $bagLookup]);
        return (object)[
            'pn' => $part->GetProductInfoResult->ManufacturerPartNumber,
            'source' => 'digikey',
            'source_pn' => $part->GetProductInfoResult->DigiKeyPartNumber,
            'manufacturer' => $part->GetProductInfoResult->Manufacturer,
            'description' => $part->GetProductInfoResult->Description,
        ];
    }
}

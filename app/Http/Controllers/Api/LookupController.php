<?php

namespace PartsAt\Http\Controllers\Api;

use PartsAt\Models;
use Illuminate\Http\Request;
use PartsAt\Http\Controllers\Controller;

class LookupController extends Controller {
    public function getIndex(Request $request) {
        $query = Models\Part::select('pn');
        foreach ($request->input('pn') as $pn) {
            $query->orWhere('pn', '=', $pn);
        }

        $result = $query->get();

        return json_encode(array_map(function($elem) {
            return $elem->pn;
        }, iterator_to_array($result)));
    }
}

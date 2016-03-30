<?php

namespace PartsAt\Http\Controllers\Frontend;

use PartsAt\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PartsAt\Models;

class IndexController extends Controller {
    public function getIndex(Request $request)
    {
        return \View::make('frontend/index', [
            'results' => $this->getSearchResults($request),
            'query' => $request->input('q')
        ]);
    }
    
    protected function getSearchResults(Request $request)
    {
        $search = $request->input('q');
        
        // Return most recent parts if no search was requested
        if (!$search) {
            return Models\Part::orderBy('created_at', 'DESC')->limit(20)->get();
        }

        // Build the search query
        $searchFields = ['pn', 'source_pn', 'description'];
        $searchQueries = array_map(function($a) {
            return 'UPPER('.$a.') LIKE ?';
        }, $searchFields);

        $searchVariables = array_map(function($a) use ($search) {
            return '%'.strtoupper($search).'%';
        }, $searchFields);

        $where = implode(' OR ', $searchQueries);

        // Do the search
        return Models\Part::whereRaw($where, $searchVariables)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}

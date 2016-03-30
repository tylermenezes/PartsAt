<?php

namespace PartsAt\Http\Controllers\Admin;

use PartsAt\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PartsAt\Services;
use PartsAt\Models;

class AddPartController extends Controller {
    public function getIndex()
    {
        return \View::make('admin/add-part', ['previous' => \Session::get('previouslyAddedPart')]);
    }

    public function postScan(Request $request)
    {
        $bag = $request->input('bag');
        $quantity = $request->input('quantity');

        $part = Services\PartBag::FindPart(trim($bag));
        $part->quantity += $quantity;
        $part->save();

        \Session::flash('previouslyAddedPart', $part);
        return redirect('/admin/add-part');
    }

    public function postManual(Request $request)
    {
        $part = new Models\Part;
        $part->pn = $request->input('pn');
        $part->source = $request->input('source');
        $part->source_pn = $request->input('source_pn');
        $part->description = $request->input('description');
        $part->manufacturer = $request->input('manufacturer');
        $part->quantity = $request->input('quantity');
        $part->save();

        \Session::flash('previouslyAddedPart', $part);
        return redirect('/admin/add-part');
    }
}

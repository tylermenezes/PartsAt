<?php

namespace PartsAt\Http\Controllers\Admin;

use PartsAt\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PartsAt\Services;
use PartsAt\Models;

class EditPartController extends Controller {
    public function getIndex($partId)
    {
        return \View::make('admin/edit-part', ['part' => Models\Part::where('id', '=', $partId)->firstOrFail()]);
    }

    public function postIndex(Request $request, $partId)
    {
        $part = Models\Part::where('id', '=', $partId)->firstOrFail();
        $part->pn = $request->input('pn');
        $part->source = $request->input('source');
        $part->source_pn = $request->input('source_pn');
        $part->description = $request->input('description');
        $part->manufacturer = $request->input('manufacturer');
        $part->quantity = $request->input('quantity');
        $part->location_broad = $request->input('location_broad');
        $part->location_narrow = $request->input('location_narrow');
        $part->save();

        return redirect('/admin/edit-part/'.$part->id);
    }

    public function getDelete($partId)
    {
        return \View::make('admin/delete-part', ['part' => Models\Part::where('id', '=', $partId)->firstOrFail()]);
    }

    public function postDelete($partId)
    {
        $part = Models\Part::where('id', '=', $partId)->firstOrFail();
        $part->delete();
        return redirect('/');
    }

    public function postPrice($partId)
    {
        $part = Models\Part::where('id', '=', $partId)->firstOrFail();
        $pricing = Services\Octopart::getPricing($part->pn);

        $part->price = isset($pricing[1]) ? $pricing[1] : null;
        $part->price_bulk = isset($pricing[10]) ? $pricing[10] : null;

        if (!isset($part->price) && isset($part->price_bulk)) {
            $part->price = $part->price_bulk * 2;
        }

        if (!isset($part->price_bulk) && isset($part->price)) {
            $part->price_bulk = $part->price * 0.9;
        }

        $part->save();
        return redirect('/admin/edit-part/'.$partId);
    }
}

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
}

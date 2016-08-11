<?php

namespace PartsAt\Http\Controllers\Admin;

use PartsAt\Services;
use PartsAt\Models;
use PartsAt\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller {
    public function getIndex()
    {
        $stats = [
            'unique' => Models\Part::count(),
            'total' => Models\Part::sum('quantity'),
            'value' => Models\Part::selectRaw('SUM(quantity * price_bulk) as value')->first()->value,
            'unpriced' => Models\Part::where('price', '=', 0.0)->count()
        ];
        return \View::make('admin/index', ['stats' => $stats]);
    }

    public function getLogin()
    {
        return \View::make('admin/login');
    }

    public function postLogin(Request $request)
    {
        if ($request->input('password') === \config('app.admin_key')) {
            \Session::set('isAuthenticated', true);
            return \Redirect::to('/admin');
        } else {
            return \View::make('admin/login', ['incorrect' => true]);
        }
    }
}

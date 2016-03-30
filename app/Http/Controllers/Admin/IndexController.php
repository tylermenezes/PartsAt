<?php

namespace PartsAt\Http\Controllers\Admin;

use PartsAt\Services;
use PartsAt\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller {
    public function getIndex()
    {
        return \View::make('admin/index');
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

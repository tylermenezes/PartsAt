<?php

\Route::group(['prefix' => '/admin/login', 'namespace' => 'Admin'], function() {
    \Route::get('/', 'IndexController@getLogin');
    \Route::post('/', 'IndexController@postLogin');
});

\Route::group(['prefix' => '/admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function(){
    \Route::controller('/add-part', 'AddPartController');
    \Route::controller('/edit-part/{part}', 'EditPartController');
    \Route::controller('/', 'IndexController');
});

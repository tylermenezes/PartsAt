<?php

\Route::group(['prefix' => '/api', 'namespace' => 'Api', 'middleware' => 'api'], function(){
    \Route::controller('/lookup', 'LookupController');
});

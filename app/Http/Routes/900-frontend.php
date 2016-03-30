<?php

\Route::group(['prefix' => '/', 'namespace' => 'Frontend'], function() {
    \Route::controller('/', 'IndexController');
});

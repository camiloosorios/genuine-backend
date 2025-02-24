<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    $path = public_path('index.html');

    if (File::exists($path)) {
        return File::get($path);
    }

    abort(404);
})->where('any', '.*');
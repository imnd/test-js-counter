<?php

use App\Http\Controllers\Web\VisitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return file_get_contents(public_path('test.html'));
});

Route::get('/stats', function () {
    return view('stats');
})->middleware('basic.admin');

Route::get('/api/stats', [VisitController::class, 'stats'])
    ->middleware('basic.admin');

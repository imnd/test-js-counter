<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return file_get_contents(public_path('test.html'));
});

Route::get('/stats', function () {
    return view('stats');
})->middleware('basic.admin');

Route::get('/api/stats', [\App\Http\Controllers\VisitController::class, 'stats'])
    ->middleware('basic.admin');

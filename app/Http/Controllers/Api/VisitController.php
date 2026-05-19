<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VisitService;

class VisitController extends Controller
{
    public function track(Request $request, VisitService $visitService)
    {
        $visitService->trackVisit($request->ip(), $request->header('User-Agent'));

        return response()->json(['status' => 'success']);
    }
}

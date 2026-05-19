<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\VisitService;

class VisitController extends Controller
{
    public function stats(VisitService $visitService)
    {
        return response()->json($visitService->getStats());
    }
}

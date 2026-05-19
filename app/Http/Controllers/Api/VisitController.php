<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VisitService;

class VisitController extends Controller
{
    protected $visitService;

    public function __construct(VisitService $visitService)
    {
        $this->visitService = $visitService;
    }

    public function track(Request $request)
    {
        $this->visitService->trackVisit($request->ip(), $request->header('User-Agent'));

        return response()->json(['status' => 'success']);
    }
}

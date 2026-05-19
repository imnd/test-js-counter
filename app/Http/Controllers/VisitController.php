<?php

namespace App\Http\Controllers;

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

    public function stats()
    {
        return response()->json($this->visitService->getStats());
    }
}

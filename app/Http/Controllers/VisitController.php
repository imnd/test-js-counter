<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class VisitController extends Controller
{
    public function track(Request $request)
    {
        $ip = $request->ip();
        
        // Handle local testing
        if ($ip === '127.0.0.1' || $ip === '::1') {
            $ip = '8.8.8.8'; // Mock IP for testing
        }

        // Fetch City using IP API
        $city = 'Unknown';
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            if ($response->successful() && $response->json('status') === 'success') {
                $city = $response->json('city');
            }
        } catch (\Exception $e) {
            // Ignore error, keep 'Unknown'
        }

        // Parse User Agent for Device
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));
        
        $device = 'Desktop';
        if ($agent->isTablet()) {
            $device = 'Tablet';
        } elseif ($agent->isMobile()) {
            $device = 'Mobile';
        } elseif ($agent->isRobot()) {
            $device = 'Bot';
        }

        Visit::create([
            'ip' => $ip,
            'city' => $city,
            'device' => $device,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function stats()
    {
        // 1. Visits by hour (last 24 hours)
        // For simplicity across DBs, we'll fetch last 24h and group in PHP
        $since = now()->subHours(24);
        $visits = Visit::where('created_at', '>=', $since)->get();
        
        $hourly = [];
        for ($i = 23; $i >= 0; $i--) {
            $hourString = now()->subHours($i)->format('Y-m-d H:00');
            $hourly[$hourString] = 0;
        }

        foreach ($visits as $visit) {
            $hourString = $visit->created_at->format('Y-m-d H:00');
            if (isset($hourly[$hourString])) {
                $hourly[$hourString]++;
            } else {
                $hourly[$hourString] = 1;
            }
        }

        // 2. City distribution (all time)
        $cityData = Visit::select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->get();

        return response()->json([
            'hourly' => [
                'labels' => array_keys($hourly),
                'data' => array_values($hourly),
            ],
            'cities' => [
                'labels' => $cityData->pluck('city'),
                'data' => $cityData->pluck('total'),
            ]
        ]);
    }
}

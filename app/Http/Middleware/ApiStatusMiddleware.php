<?php

namespace App\Http\Middleware;

use App\Http\Controllers\HomeController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = Http::withHeaders([
            'x-apisports-key' => getenv('APIFOOTBALL_API_KEY'),
        ])->get('https://v3.football.api-sports.io/status');
        $response = json_decode($response->body())->response;

        if(!empty($response)) {
            if($response->subscription->active){
                $current = $response->requests->current;
                $limit = $response->requests->limit_day;
                if($current < $limit){
                    return $next($request);
                }
            }
        }
        return redirect()->route('home')->with('daily_limit','exceeded');
    }
}

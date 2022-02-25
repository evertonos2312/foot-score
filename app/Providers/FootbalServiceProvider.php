<?php

namespace App\Providers;

use App\FootballScore\APIFootball;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class FootbalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('football', function()
        {
            $client = new Client([
                'base_uri'  =>  'https://api-football-v1.p.rapidapi.com',
                'headers'   =>  [
                    "X-RapidAPI-Host" => "api-football-v1.p.rapidapi.com",
                    'X-RapidAPI-Key' => getenv('APIFOOTBALL_API_KEY')
                ]
            ]);

            return new APIFootball($client);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

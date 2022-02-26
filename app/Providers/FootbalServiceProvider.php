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
                'base_uri'  =>  'https://v3.football.api-sports.io',
                'headers'   =>  [
                    'x-rapidapi-host' =>'v3.football.api-sports.io',
                    'x-apisports-key' => getenv('APIFOOTBALL_API_KEY')
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

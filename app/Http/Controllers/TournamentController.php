<?php

namespace App\Http\Controllers;

use App\FootballScore\APIFootball;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TournamentController extends Controller
{
    protected $football;
    public function __construct(\Football $football)
    {
        $this->football = $football;
    }

    public function championsLeague()
    {
        echo 'f';
        $league =   $this->football->getLeague(2);
        echo '<pre>';
        print_r($league);
        echo '</pre>';
        die();

    }
}

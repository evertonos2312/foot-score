<?php

namespace App\Http\Controllers;

use App\FootballScore\APIFootball;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use \Football;

class TournamentController extends Controller
{

    public function championsLeague()
    {
        $prepareChampionsLeague =   Football::getLeague(2);
        $basicData = (array)$prepareChampionsLeague['league'];

        return view('web.champions', $basicData);

    }
}

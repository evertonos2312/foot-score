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
        $prepareChampionsLeague =   Football::getLeague(2)->all();
        $basicData = $prepareChampionsLeague['league'];

        return view('web.champions', $basicData);

    }

    public function premierLeague()
    {
        $getCurrentSeason = Football::getLeague(39)->all();
        $leagueStandings = [];
        if (!empty($getCurrentSeason)) {
            $leagueID = $getCurrentSeason['league']->id;
            $season = $getCurrentSeason['seasons']['0']->year;
            $leagueStandings = Football::getLeagueStandings($leagueID, $season)->all()['league']->standings['0'];
        }
        return view('web.premier', ["standings" => $leagueStandings]);
    }
}

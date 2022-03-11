<?php

namespace App\Http\Controllers;

use App\FootballScore\APIFootball;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use \Football;

class TournamentController extends Controller
{
    public array $data;

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
            $leagueID = $getCurrentSeason['id'];
            $season = $getCurrentSeason['year_current_season'];

            $leagueStandings = Football::getLeagueStandings($leagueID, $season)->all();
            $leagueCurrentRound = Football::getCurrentRound($leagueID, $season)->all()[0];
//            $leagueFixtures = Football::getLeagueFixtures($leagueID, $season, $leagueCurrentRound)->all();
        }
        $this->data['standings'] = $leagueStandings;
        return view('web.premier', $this->data);
    }
}

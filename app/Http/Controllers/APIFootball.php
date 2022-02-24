<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class APIFootball extends Controller
{
    protected $client;

    public function __construct(Client $client )
    {
        $this->client = $client;
    }



    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function run($uri, $type = 'GET')
    {
        return json_decode( $this->client->request($type, $uri)->getBody() );
    }



    ##COMPETITION/LEAGUE

    /**
     * List all available competitions.
     *
     * @return Collection
     */
    public function getLeagues(): Collection
    {
        $leagues = $this->run("v2/leagues" );
        return collect($leagues->api);
    }

    /**
     * List one particular competition.
     *
     * @param integer $leagueID
     * @return Collection
     */
    public function getLeague(int $leagueID): Collection
    {
        $league = $this->run("v2/leagues/league/{$leagueID}");
        return collect($league->api);
    }

    /**
     * Show Standings for a particular competition
     *
     * @param integer $leagueID
     * @return Collection
     */
    public function getLeagueStandings(int $leagueID): Collection
    {
        $leagueStandings = $this->run("v2/leagueTable/{$leagueID}");
        return collect($leagueStandings->api);
    }

    /**
     * List all matches for a particular competition.
     *
     * @param integer $leagueID
     * @return Collection
     */
    public function getLeagueMatches(int $leagueID): Collection
    {
        $leagueMatches = $this->run("v2/fixtures/league/{$leagueID}");
        return collect($leagueMatches->api);
    }



    ##FIXTURES/MATCHES

    /**
     * List matches across (a set of) competitions.
     *
     * @param $date
     * @return Collection
     */
    public function getMatches($date): Collection
    {
        $matches = $this->run("v2/fixtures/date/{$date}");
        return collect($matches->matches);
    }

    /**
     * Show one particular match.
     *
     * @param integer $matchID
     * @return Collection
     */
    public function getMatche(int $matchID): Collection
    {
        $matche = $this->run("/v2/fixtures/id/{$matchID}");
        return collect($matche->api);
    }


    ##TEAM

    /**
     * Show one particular team.
     *
     * @param integer $teamID
     * @return Collection
     */
    public function getTeam(int $teamID): Collection
    {
        $team = $this->run("v2/teams/team/{$teamID}");
        return collect($team->api);
    }

    /**
     * Show all matches for a particular team.
     *
     * @param integer $teamID
     * @param array $filter
     * @return Collection
     */
    public function getMatchesForTeam(int $teamID): Collection
    {
        $matches = $this->run("/v2/fixtures/team/{$teamID}");
        return collect($matches->api);
    }

    /**
     * List all matches for a particular competition with date
     *
     * @param integer $leagueID
     * @param date $date
     * @return Collection
     */
    public function getLeagueMatchesWithDate(int $leagueID,$date)
    {
        $leagueMatches = $this->run("v2/fixtures/league/{$leagueID}/{$date}");
        return collect($leagueMatches->api);
    }
}
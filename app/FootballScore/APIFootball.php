<?php

namespace App\FootballScore;

use App\Models\LeagueStandings;
use App\Models\LeagueStandingsUpdate;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use App\Models\LeagueInformation;
use Illuminate\Support\Facades\Http;
use Closure;

class APIFootball
{
    protected $client;

    public function __construct(Client $client )
    {
        $this->client = $client;
    }



    /**
     * Handle an incoming request.
     * @return false
     */
    private function checkStatus(): bool
    {
        $response = Http::withHeaders([
            'x-apisports-key' => getenv('APIFOOTBALL_API_KEY'),
        ])->get('https://v3.football.api-sports.io/status');
        if(!empty($response->body())){
            $response = json_decode($response->body())->response;

            if(!empty($response)) {
                if($response->subscription->active){
                    $current = $response->requests->current;
                    $limit = $response->requests->limit_day;
                    if($current < $limit){
                        return true;
                    }
                }
            }
        }
        return false;
    }



    /**
     * @throws GuzzleException
     */
    public function run($uri, array $optionalParams = [], $type = 'GET')
    {
        if($this->checkStatus()){
            return json_decode( $this->client->request($type, $uri, ['query' => $optionalParams])->getBody());
        }
        return redirect()->route('home')->with('daily_limit', 'exceeded');
    }



    ##COMPETITION/LEAGUE

    /**
     * List all available competitions.
     *
     * @return Collection
     * @throws GuzzleException
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
     * @throws GuzzleException
     */
    public function getLeague(int $leagueID): Collection
    {
        $leagueInformation = LeagueInformation::where('id', $leagueID)->first();
        if(!is_null($leagueInformation)){
            $dateNow =  date_create();
            $dateOld =  date_create($leagueInformation->updated_at);
            $dateDiff = date_diff($dateNow, $dateOld);

            if($dateDiff->d < 7) {
                return collect($leagueInformation);
            }
        }
        $optionalParams = ['id' => $leagueID , 'current' => 'true'];
        $league = $this->run("leagues", $optionalParams);

        (new LeagueInformation)->storeLeague($league);
        return collect(LeagueInformation::where('id', $leagueID)->first());
    }

    /**
     * Show Standings for a particular competition
     *
     * @param integer $leagueID
     * @return Collection
     * @throws GuzzleException
     */
    public function getLeagueStandings(int $leagueID, int $season): Collection
    {
        $leagueStandings = LeagueStandings::where('league_id', $leagueID)->where('season', $season)->get();
        if($leagueStandings->isNotEmpty()){
            $lastUpdate = LeagueStandingsUpdate::where('league_id', $leagueID)->first();
            if(!is_null($lastUpdate)){
                $dateNow =  date_create();
                $dateOld =  date_create($lastUpdate->updated_at);
                $dateDiff = date_diff($dateNow, $dateOld);
                if($dateDiff->i < 60) {
                    return $leagueStandings;
                }
            }
        }

        $params = ['league' => $leagueID, 'season' => $season];
        $league = $this->run("standings", $params);

        (new LeagueStandings)->storeStanding($league);
        return LeagueStandings::where('league_id', $leagueID)->where('season', $season)->get();
    }

    public function updateTeamLogo(int $leagueID, int $season): \Illuminate\Http\JsonResponse
    {
        $leagueStandings = LeagueStandings::select('team_logo')->where('league_id', $leagueID)->whereNull('team_logo')->where('season', $season)->get();
        $lastUpdate = LeagueStandingsUpdate::where('league_id', $leagueID)->first();

        if(!is_null($lastUpdate)){
            $dateNow =  date_create();
            $dateOld =  date_create($lastUpdate->updated_at);
            $dateDiff = date_diff($dateNow, $dateOld);
            if($dateDiff->d > 120 || $leagueStandings->isNotEmpty()) {
                $params = ['league' => $leagueID, 'season' => $season];
                $league = $this->run("standings", $params);
                $leagueStandingsModel = new LeagueStandings();
                $leagueStandingsModel->storeTeamsLogo($league);
                return response()->json(['message' => "Teams logos from league $leagueID updated"]);
            }
        }
        return response()->json(['message' => "No updates found for league $leagueID"]);

    }

    /**
     * List all matches for a particular competition.
     *
     * @param integer $leagueID
     * @return Collection
     */
    public function getLeagueFixtures(int $leagueID, int $season, string $round): Collection
    {
        $params = [
            'league' => $leagueID,
            'season' => $season,
            'round' => $round,
            'timezone' => 'America/Sao_Paulo'
        ];
        $leagueFixtures = $this->run("fixtures", $params);
//        echo '<pre>';
//        print_r($leagueFixtures);
//        echo '</pre>';
//        die();
        return collect($leagueFixtures->response[0]);
    }



    ##FIXTURES/MATCHES

    /**
     * List matches across (a set of) competitions.
     *
     * @param $date
     * @return Collection
     */
    public function getCurrentRound(int $leagueID, int $season): Collection
    {
        $leagueInfo = LeagueInformation::select('current_round', 'updated_at')->where('id', $leagueID)->first();

        if(!is_null($leagueInfo) && !empty($leagueInfo['current_round'])){
            $dateNow =  date_create();
            $dateOld =  date_create($leagueInfo->updated_at);
            $dateDiff = date_diff($dateNow, $dateOld);

            if($dateDiff->d < 1) {
                return collect($leagueInfo['current_round']);
            }
        }
        $params = [
            'league' => $leagueID,
            'season' => $season,
            'current' => 'true',
        ];
        $rounds = $this->run("fixtures/rounds", $params);

        $LeagueInformationModel = LeagueInformation::find($leagueID);
        $LeagueInformationModel->current_round = $rounds->response[0];
        $LeagueInformationModel->save();

        return LeagueInformation::select('current_round')->where('id', $leagueID)->where('season', $season)->get();
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

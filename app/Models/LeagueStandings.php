<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LeagueStandings extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'league_id',
        'season',
        'rank',
        'team_id',
        'team_name',
        'team_logo',
        'points',
        'goalsDiff',
        'form',
        'played',
        'win',
        'draw',
        'lose',
        'goals_for',
        'goals_against',
        'points_percent'
    ];

    public function storeStanding(object $data)
    {
        foreach ($data->response[0]->league->standings['0'] as $standing){
            $pointsPossible = $standing->all->played * 3;
            $pointsPercent = ($standing->points /$pointsPossible) * 100;
            LeagueStandings::updateOrCreate(
                [
                    'team_id' => $standing->team->id,
                ],
                [
                'league_id' => $data->response[0]->league->id,
                'season' =>  $data->response[0]->league->season,
                'rank' => $standing->rank,
                'team_name' => $standing->team->name,
                'points' => $standing->points,
                'goalsDiff' => $standing->goalsDiff,
                'form' => $standing->form,
                'played' => $standing->all->played,
                'win' => $standing->all->win,
                'draw' => $standing->all->draw,
                'lose' => $standing->all->lose,
                'goals_for' => $standing->all->goals->for,
                'goals_against' => $standing->all->goals->against,
                'points_percent' => $pointsPercent
            ]);
        }
        LeagueStandingsUpdate::updateOrCreate([
            'league_id' => $data->response[0]->league->id
        ]);
    }

    public function storeTeamsLogo(object $data)
    {
        $lastUpdate = LeagueStandingsUpdate::where('league_id', $data->response[0]->league->id)->first();
        $updateLogo = true;

        if(!is_null($lastUpdate)){
            $dateNow =  date_create();
            $dateOld =  date_create($lastUpdate->updated_at);
            $dateDiff = date_diff($dateNow, $dateOld);
            if($dateDiff->d > 120 ) {
                $updateLogo = true;
            }

        }
        foreach ($data->response[0]->league->standings['0'] as $standing){
            if($updateLogo) {
                $path = $standing->team->logo;
                $logo = file_get_contents($path);
                $size = getimagesize($path);
                $extension = image_type_to_extension($size[2]);
                $logoUniqueName = date('mdYHis').uniqid().$standing->team->id.$extension;
                Storage::disk('public')->put($logoUniqueName, $logo);
                $updateLogoModel = LeagueStandings::where('team_id',$standing->team->id)->first();
                $updateLogoModel->team_logo = $logoUniqueName;
                $updateLogoModel->save();
            }
        }
    }
}

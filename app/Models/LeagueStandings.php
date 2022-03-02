<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LeagueStandings extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    public function storeStanding(object $data)
    {
        $lastUpdate = LeagueStandingsUpdate::where('league_id', $data->response[0]->league->id)->first();
        $updateLogo = true;
        if(!is_null($lastUpdate)){
            $dateNow =  date_create();
            $dateOld =  date_create($lastUpdate->updated_at);
            $dateDiff = date_diff($dateNow, $dateOld);

            if($dateDiff->d < 120 ) {
                $updateLogo = false;
            }

        }
        foreach ($data->response[0]->league->standings['0'] as $standing){
            $model = new LeagueStandings();
            $model->league_id = $data->response[0]->league->id;
            $model->season = $data->response[0]->league->season;
            $model->rank = $standing->rank;
            $model->team_id = $standing->team->id;
            $model->team_name = $standing->team->name;
            $model->points = $standing->points;
            $model->goalsDiff = $standing->goalsDiff;
            $model->form = $standing->form;
            $model->played = $standing->all->played;
            $model->win = $standing->all->win;
            $model->draw = $standing->all->draw;
            $model->lose = $standing->all->lose;
            $model->goals_for = $standing->all->goals->for;
            $model->goals_against = $standing->all->goals->against;
            if($updateLogo) {
                $path = $standing->team->logo;
                $logo = file_get_contents($path);
                $size = getimagesize($path);
                $extension = image_type_to_extension($size[2]);
                $logoUniqueName = date('mdYHis').uniqid().$standing->team->id.$extension;
                Storage::disk('public')->put($logoUniqueName, $logo);
                $model->team_logo = $logoUniqueName;
            }
            $model->save();
        }
        $updateStandings = new LeagueStandingsUpdate();
        $updateStandings->league_id = $data->response[0]->league->id;
        $updateStandings->save();
    }
}

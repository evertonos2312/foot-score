<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'type',
        'logo',
        'country_name',
        'year_current_season',
        'season_start',
        'season_end',
        'current',
    ];

    public function storeLeague(object $data)
    {
        $leagueStore = [
            'id' => $data->response[0]->league->id,
            'name' => $data->response[0]->league->name,
            'type' => $data->response[0]->league->type,
            'logo' => $data->response[0]->league->logo,
            'country_name' => $data->response[0]->country->name,
            'year_current_season' => $data->response[0]->seasons['0']->year,
            'season_start' => $data->response[0]->seasons['0']->start,
            'season_end' => $data->response[0]->seasons['0']->end,
            'current' => $data->response[0]->seasons['0']->current,
        ];
        $this->fill($leagueStore);
        $this->save();
    }
}

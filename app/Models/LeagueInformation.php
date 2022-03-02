<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        $path = $data->response[0]->league->logo;
        $logo = file_get_contents($path);
        $size = getimagesize($path);
        $extension = image_type_to_extension($size[2]);
        $logoUniqueName = date('mdYHis').uniqid().$data->response[0]->league->id.$extension;
        Storage::disk('public')->put($logoUniqueName, $logo);

        $leagueStore = [
            'name' => $data->response[0]->league->name,
            'type' => $data->response[0]->league->type,
            'logo' => $logoUniqueName,
            'country_name' => $data->response[0]->country->name,
            'year_current_season' => $data->response[0]->seasons['0']->year,
            'season_start' => $data->response[0]->seasons['0']->start,
            'season_end' => $data->response[0]->seasons['0']->end,
            'current' => $data->response[0]->seasons['0']->current,
        ];
        $exists = $this->find($data->response[0]->league->id);
        if(!$exists){
            $leagueStore['id'] = $data->response[0]->league->id;
            $this->fill($leagueStore);
            $this->save();
        } else {
            LeagueInformation::where('id', $data->response[0]->league->id)->update($leagueStore);
        }
    }
}

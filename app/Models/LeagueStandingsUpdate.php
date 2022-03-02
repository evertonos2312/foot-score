<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueStandingsUpdate extends Model
{
    use HasFactory;

    protected $primaryKey = 'league_id';
    public $incrementing = false;
    protected $table = 'league_standings_update';
    protected $fillable = [
        'league_id',
    ];
}

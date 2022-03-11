<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/liga-dos-campeos', [TournamentController::class, 'championsLeague'])->name('ligaDosCampeos');
Route::get('/liga-inglesa', [TournamentController::class, 'premierLeague'])->name('ligaInglesa');
Route::get('/update-logos/{leagueID}/{season}',
    function(int $leagueID, int $season) {
        $response = Football::updateTeamLogo($leagueID, $season);
        echo $response->throwResponse();
    }
);

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public array $data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     * @param string $exceed
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $msg = Session::get('daily_limit');
        $this->data['daily_limit'] = $msg;
        return view('web.index', $this->data);
    }
}

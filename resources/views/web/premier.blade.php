@extends('layouts.app')
@section('content')

<div class="site-section bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="widget-next-match">
                    <table class="table custom-table">
                        <thead>
                        <tr>
                            <th>Classificação</th>
                            <th>P</th>
                            <th>J</th>
                            <th>V</th>
                            <th>E</th>
                            <th>D</th>
                            <th>GP</th>
                            <th>GC</th>
                            <th>SG</th>
                            <th>%</th>
                            <th>ÚLT. JOGOS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($standings as $standing)
                            <tr class="text-white">
                                <td>
                                    {{$standing->rank}}
                                    <img class="" style="width:24px;height:24px" src="{{ asset('storage/'.$standing->team_logo)}}" alt="{{$standing->team_name}}">
                                    <strong class="text-white">{{$standing->team_name}}</strong>
                                </td>
                                <td>{{$standing->points}}</td>
                                <td>{{$standing->played}}</td>
                                <td>{{$standing->win}}</td>
                                <td>{{$standing->draw}}</td>
                                <td>{{$standing->lose}}</td>
                                <td>{{$standing->goals_for}}</td>
                                <td>{{$standing->goals_against}}</td>
                                <td>{{$standing->goalsDiff}}</td>
                                <td>{{$standing->points_percent}}</td>
                                <td>{{$standing->form}}</td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="widget-next-match">
                    <div class="widget-title">
                        <h3>Next Match</h3>
                    </div>
                    <div class="widget-body mb-3">
                        <div class="widget-vs">
                            <div class="d-flex align-items-center justify-content-around justify-content-between w-100">
                                <div class="team-1 text-center">
                                    <img src="images/logo_1.png" alt="Image">
                                    <h3>Football League</h3>
                                </div>
                                <div>
                                    <span class="vs"><span>VS</span></span>
                                </div>
                                <div class="team-2 text-center">
                                    <img src="images/logo_2.png" alt="Image">
                                    <h3>Soccer</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center widget-vs-contents mb-4">
                        <h4>World Cup League</h4>
                        <p class="mb-5">
                            <span class="d-block">December 20th, 2020</span>
                            <span class="d-block">9:30 AM GMT+0</span>
                            <strong class="text-primary">New Euro Arena</strong>
                        </p>

                        <div id="date-countdown2" class="pb-1"></div>
                    </div>
                </div>
                </div>

        </div>
    </div>
</div>

@endsection

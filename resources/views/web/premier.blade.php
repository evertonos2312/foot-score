@extends('layouts.app')
@section('content')

<div class="site-section bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
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
                <div class="col-lg-6">
                <div class="widget-next-match">
                    <table class="table custom-table">
                        <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Time</th>
                            <th>V</th>
                            <th>E</th>
                            <th>D</th>
                            <th>PTS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($standings as $standing)
                        <tr class="text-white">
                            <td>{{$standing->rank}}</td>
                            <td>
                                <img class="" style="width:24px;height:24px" src="{{$standing->team->logo}}" alt="{{$standing->team->name}}">
                                <strong class="text-white">{{$standing->team->name}}</strong>
                            </td>
                            <td>{{$standing->all->win}}</td>
                            <td>{{$standing->all->draw}}</td>
                            <td>{{$standing->all->lose}}</td>
                            <td>{{$standing->points}}</td>
                        </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header"><img class="img-responsive img-rounded" style="max-height: 50px; max-width: 50px;" src="{{$logo}}" alt="{{$name}}">  {{$name}}</div>

                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

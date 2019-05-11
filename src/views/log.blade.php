@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col">
            <h3>Laravel error log</h3>
        </div>
        <div class="col text-right">
            <a href="{{ route('fastleo.log.clear') }}" class="btn btn-warning">Очистить log</a>
        </div>
    </div>
    <div class="row">
        <div class="col" style="max-height: 80vh; overflow-y: scroll;  overflow-x: hidden;">
            @foreach($logs as $log)
                <small>{{ $log }}</small><br><br>
            @endforeach
        </div>
    </div>

@endsection
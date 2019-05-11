@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col">
            <table class="table table-hover">
                @foreach($params as $param)
                    <tr>
                        <td width="200">{{ $param['title'] }}</td>
                        <td>{{ $param['value'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="{{ route('fastleo.info.clear') }}" class="btn btn-warning">Очистить кэш</a>
            <a href="{{ route('fastleo.log.clear') }}" class="btn btn-warning">Очистить log</a>
        </div>
    </div>

@endsection
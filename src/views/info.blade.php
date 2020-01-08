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
            <br><br><br>
        </div>
    </div>

    @foreach(app()->models as $model => $data)
        @if($data->fastleo and $data->fastleo_description)
            <div class="row">
                <div class="col">
                    <h4>{{ $data->fastleo }}</h4>
                    <p>{{ $data->fastleo_description }}</p>
                    <br>
                </div>
            </div>
        @endif
    @endforeach

@endsection
@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col-12">
            <h3>{{ $title }} / {{ isset($row) ? 'Редактировать' : 'Создать' }} запись</h3>
            @if(session()->flash('message'))
                <div class="alert alert-success">{{ session()->get('message') }}</div>
            @endif
        </div>
    </div>
    <br>
    <form action="" method="post">
        <div class="row">
            <div class="col-12">
                {{ csrf_field() }}
                @foreach($columns as $column => $data)
                    @if(in_array($data['type'], ['text','tinytext','mediumtext','longtext','json']))
                        @include('fastleo::rows.text', [$columns, $column, $data, 'row' => $row ?? null])
                    @elseif(in_array($data['type'], ['number', 'integer', 'int', 'tinyint', 'bigint', 'float', 'double', 'decimal']))
                        @include('fastleo::rows.integer', [$columns, $column, $data, 'row' => $row ?? null])
                    @elseif(in_array($data['type'], ['checkbox', 'boolean']))
                        @include('fastleo::rows.checkbox', [$columns, $column, $data, 'row' => $row ?? null])
                    @elseif(in_array($data['type'], ['select', 'multiselect']))
                        @include('fastleo::rows.select', [$columns, $column, $data, 'row' => $row ?? null])
                    @elseif(in_array($data['type'], ['include']))
                        @include('fastleo::rows.include', [$columns, $column, $data, 'row' => $row ?? null])
                    @else
                        @include('fastleo::rows.input', [$columns, $column, $data, 'row' => $row ?? null])
                    @endif
                @endforeach
            </div>
            <div class="col-2"></div>
            <div class="col-10">
                <input type="submit" class="btn btn-success" value="Сохранить">
                <button type="submit" name="_return" class="btn btn-warning" value="true">Сохранить и выйти</button>
                <a href="/fastleo/app/{{ $name }}?{{ request()->getQueryString() }}" class="btn btn-primary">Вернуться</a>
            </div>
        </div>
    </form>

@endsection
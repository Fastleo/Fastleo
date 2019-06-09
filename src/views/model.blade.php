@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col-2">
            <h3>{{ isset($model['title']) ? $model['title'] : $model_title }}</h3>
        </div>
        <div class="col-6 text-right">
            <form action="" method="get" id="search" @if(request()->get('search') == null) style="display: none;" @endif>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Поиск" value="@if(request()->get('search')){{ request()->get('search') }}@endif">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success form-control">Найти</button>
                    </div>
                    <div class="input-group-append">
                        <a href="/fastleo/app/{{ $model_name }}" class="form-control btn btn-warning">Сброс</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-4 text-right">
            <div class="btn-group">
                <a href="/fastleo/app/{{ $model_name }}/add?{{ request()->getQueryString() }}" class="btn btn-success">Добавить запись</a>
                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                <div class="dropdown-menu">
                    @if(!isset($model_columns['menu']))
                        <a class="dropdown-item" href="/fastleo/app/{{ $model_name }}/menu_add">Добавить видимость</a>
                    @else
                        <a class="dropdown-item" href="/fastleo/app/{{ $model_name }}/menu_on">Включить все</a>
                        <a class="dropdown-item" href="/fastleo/app/{{ $model_name }}/menu_off">Выключить все</a>
                    @endif
                    <div class="dropdown-divider"></div>
                    @if(!isset($model_columns['sort']))
                        <a class="dropdown-item" href="/fastleo/app/{{ $model_name }}/sorting_add">Добавить сортировку</a>
                    @else
                        <a class="dropdown-item" href="/fastleo/app/{{ $model_name }}/sorting_fix">Исправить сортировку</a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/fastleo/app/{{ $model_name }}/rows_export?{{ request()->getQueryString() }}" download>Экспортировать данные</a>
                    <a class="dropdown-item" href="" id="import">Импортировать данные</a>
                </div>
            </div>
            <a href="" onclick="$('#search').toggle(); return false;" class="btn btn-info">Поиск</a>
        </div>
    </div>
    <form action="/fastleo/app/{{ $model_name }}/rows_import?{{ request()->getQueryString() }}" method="post" enctype="multipart/form-data" id="form" style="display: none;">
        {{ csrf_field() }}
        <input type="file" name="import">
    </form>
    <div class="row">
        <div class="col">
            <br>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th width="80"></th>
                    @php $i = 1; @endphp
                    @foreach($model_columns as $c => $t)
                        @if(!isset($f[$c]['visible']) or $f[$c]['visible'] == true)
                            @if(!in_array($t, $exclude_type) and !in_array($c, $exclude_name) and $i < 10)
                                <th>@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ str_replace("_", " ", ucfirst($c)) }}@endif</th>
                                @php ++$i; @endphp
                            @endif
                        @endif
                    @endforeach
                    <th width="80"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>
                            @if(isset($row->sort))
                                <a href="/fastleo/app/{{ $model_name }}/up/{{ $row->id }}?{{ request()->getQueryString() }}"><i class="fas fa-arrow-up fa-xs"></i></a>
                            @endif
                            @if(isset($row->menu) or is_null($row->menu))
                                <a href="/fastleo/app/{{ $model_name }}/menu/{{ $row->id }}?{{ request()->getQueryString() }}" style="color:{{ $row->menu == 1 ? 'green' : 'red' }}"><i class="far fa-dot-circle fa-xs"></i></a>
                            @endif
                            @if(isset($row->sort))
                                <a href="/fastleo/app/{{ $model_name }}/down/{{ $row->id }}?{{ request()->getQueryString() }}"><i class="fas fa-arrow-down fa-xs"></i></a>
                            @endif
                        </td>
                        @php $i = 1; @endphp
                        @foreach($model_columns as $c => $t)
                            @if(!isset($f[$c]['visible']) or $f[$c]['visible'] == true)
                                @if(!in_array($t, $exclude_type) and !in_array($c, $exclude_name) and $i < 10)
                                    <td>
                                        <a href="/fastleo/app/{{ $model_name }}/edit/{{ $row->id }}?{{ request()->getQueryString() }}">{{ $row->{$c} }}</a>
                                    </td>
                                    @php ++$i; @endphp
                                @endif
                            @endif
                        @endforeach
                        <td>
                            <a href="/fastleo/app/{{ $model_name }}/edit/{{ $row->id }}?{{ request()->getQueryString() }}"><i class="far fa-edit"></i></a>
                            <a href="/fastleo/app/{{ $model_name }}/delete/{{ $row->id }}?{{ request()->getQueryString() }}" onclick="return confirm('Удалить запись?');"><i class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            {{ $rows->appends(request()->all())->links() }}
        </div>
    </div>

@endsection
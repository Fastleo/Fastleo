@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col">
            <h3>{{ $title }}</h3>
        </div>
        <div class="col text-right">
            <form action="" method="get" id="search" @if(is_null(request()->get('_search'))) style="display: none;" @endif>
                <div class="input-group">
                    <input type="text" name="_search" class="form-control" placeholder="Поиск" value="@if(request()->has('_search')){{ request()->get('_search') }}@endif">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success form-control">Найти</button>
                    </div>
                    <div class="input-group-append">
                        <a href="/fastleo/app/{{ $name }}" class="form-control btn btn-warning">Сброс</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col text-right">
            <div class="btn-group">
                <a href="/fastleo/app/{{ $name }}/add?{{ request()->getQueryString() }}" class="btn btn-success">Добавить запись</a>
                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                <div class="dropdown-menu">
                    @if(isset($columns['menu']))
                        <a class="dropdown-item" href="/fastleo/app/{{ $name }}/menu_on">Включить все</a>
                        <a class="dropdown-item" href="/fastleo/app/{{ $name }}/menu_off">Выключить все</a>
                        <div class="dropdown-divider"></div>
                    @endif
                    @if(isset($columns['sort']))
                        <a class="dropdown-item" href="/fastleo/app/{{ $name }}/sorting_fix">Исправить сортировку</a>
                        <div class="dropdown-divider"></div>
                    @endif
                    <a class="dropdown-item" href="/fastleo/app/{{ $name }}/rows_export?{{ request()->getQueryString() }}" download>Экспортировать данные</a>
                    <a class="dropdown-item" href="" id="import">Импортировать данные</a>
                </div>
            </div>
            <a href="" onclick="$('#search').toggle(); return false;" class="btn btn-info">Поиск</a>
        </div>
    </div>
    <form action="/fastleo/app/{{ $name }}/rows_import?{{ request()->getQueryString() }}" method="post" enctype="multipart/form-data" id="form" style="display: none;">
        {{ csrf_field() }}
        <input type="file" name="import">
    </form>
    <br>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th width="80"></th>
                        @foreach($columns as $c => $t)
                            @if(!isset($t['visible']) or $t['visible'] == true)
                                @if(!in_array($c, config('fastleo.exclude.list_name')))
                                    <th class="text-nowrap">{{ $t['title'] ?? str_replace("_", " ", ucfirst($c)) }}</th>
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
                                @if(array_key_exists('sort', $columns))
                                    <a href="/fastleo/app/{{ $name }}/up/{{ $row->id }}?{{ request()->getQueryString() }}"><i class="fas fa-arrow-up fa-xs"></i></a>
                                @endif
                                @if(array_key_exists('menu', $columns))
                                    <a href="/fastleo/app/{{ $name }}/menu/{{ $row->id }}?{{ request()->getQueryString() }}" style="color:{{ $row->menu == 1 ? 'green' : 'red' }}"><i class="far fa-dot-circle fa-xs"></i></a>
                                @endif
                                @if(array_key_exists('sort', $columns))
                                    <a href="/fastleo/app/{{ $name }}/down/{{ $row->id }}?{{ request()->getQueryString() }}"><i class="fas fa-arrow-down fa-xs"></i></a>
                                @endif
                            </td>
                            @foreach($columns as $c => $t)
                                @if(!isset($t['visible']) or $t['visible'] == true)
                                    @if(!in_array($c, config('fastleo.exclude.list_name')))
                                        <td>
                                            @if(\Str::endsWith($c, '_id'))
                                                @php $method = substr($c, 0, -3); @endphp
                                                @if(method_exists($row, $method))
                                                    <a href="/fastleo/app/{{ $name }}/edit/{{ $row->id }}?{{ request()->getQueryString() }}">
                                                        {{ $row->{$method}->title ?? $row->{$method}->name ?? $row->{$c} }}
                                                    </a>
                                                @endif
                                            @else
                                                <a href="/fastleo/app/{{ $name }}/edit/{{ $row->id }}?{{ request()->getQueryString() }}">{{ $row->{$c} }}</a>
                                            @endif
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                            <td>
                                <a href="/fastleo/app/{{ $name }}/edit/{{ $row->id }}?{{ request()->getQueryString() }}"><i class="far fa-edit"></i></a>
                                <a href="/fastleo/app/{{ $name }}/delete/{{ $row->id }}?{{ request()->getQueryString() }}" onclick="return confirm('Удалить запись?');"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            {{ $rows->appends(request()->all())->links() }}
        </div>
    </div>

@endsection
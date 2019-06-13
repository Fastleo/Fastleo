@extends('fastleo::app')

@section('content')

    <div class="row">
        <div class="col">
            <h3>{{ isset($model['title']) ? $model['title'] : $model_title }} / @if(isset($row_id)){{ 'Редактировать' }}@else{{ 'Создать' }}@endif запись</h3>
            @if(session()->has('message'))
                <div class="alert alert-success">{{ session()->get('message') }}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            &nbsp;
        </div>
    </div>
    <div class="row">
        <div class="col">
            <form action="" method="post">
                {{ csrf_field() }}
                @if(count($model_columns) > 0)
                    @foreach($model_columns as $c => $t)
                        @if(!in_array($t, $exclude_type) and !in_array($c, $exclude_name))
                            @if(in_array($t, ['text','longtext']) or (isset($f[$c]['type']) and in_array($f[$c]['type'], ['text','longtext'])))
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="{{ $c }}">@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ ucfirst($c) }}@endif:</label>
                                    <div class="col-sm-9">
                                        <textarea name="{{ $c }}" id="{{ $c }}" class="form-control @if(isset($f[$c]['tinymce']) and $f[$c]['tinymce'] == true){{ 'tinymce' }}@endif" rows="10" @if(isset($f[$c]['disabled']) and $f[$c]['disabled'] == true){{ 'disabled' }}@endif @if(isset($f[$c]['required']) and $f[$c]['required'] == true){{ 'required' }}@endif>@if(isset($row->{$c})){!! trim($row->{$c}) !!}@endif</textarea>
                                        @if(isset($f[$c]['description']) and $f[$c]['description'] != '')
                                            <small id="emailHelp" class="form-text text-muted">{{ $f[$c]['description'] }}</small>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            @elseif(isset($f[$c]['type']) and in_array($f[$c]['type'], ['integer', 'int', 'tinyint', 'float', 'double', 'decimal']))
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="{{ $c }}">@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ ucfirst($c) }}@endif:</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input type="number" name="{{ $c }}" id="{{ $c }}" class="form-control" placeholder="@if(isset($f[$c]['placeholder'])){{ $f[$c]['placeholder'] }}@else{{ $t }}@endif" value="@if(isset($row->{$c})){{ $row->{$c} }}@endif" @if(isset($f[$c]['disabled']) and $f[$c]['disabled'] == true){{ 'disabled' }}@endif @if(isset($f[$c]['required']) and $f[$c]['required'] == true){{ 'required' }}@endif>
                                            @if(isset($f[$c]['description']) and $f[$c]['description'] != '')
                                                <small id="emailHelp" class="form-text text-muted">{{ $f[$c]['description'] }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @elseif(isset($f[$c]['type']) and in_array($f[$c]['type'], ['checkbox', 'boolean']))
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label class="form-check-label" for="{{ $c }}">@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ ucfirst($c) }}@endif:</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input type="hidden" name="{{ $c }}" value="0">
                                            <input type="checkbox" name="{{ $c }}" class="form-check-input" id="{{ $c }}" placeholder="@if(isset($f[$c]['placeholder'])){{ $f[$c]['placeholder'] }}@else{{ $t }}@endif" value="1" @if(isset($f[$c]['disabled']) and $f[$c]['disabled'] == true){{ 'disabled' }}@endif @if(isset($f[$c]['required']) and $f[$c]['required'] == true){{ 'required' }}@endif @if(isset($row->{$c}) and $row->{$c} == 1){{ 'checked' }}@endif>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @elseif(isset($f[$c]['type']) and in_array($f[$c]['type'], ['select', 'multiselect']))
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="{{ $c }}">@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ ucfirst($c) }}@endif:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" id="{{ $c }}" @if(isset($f[$c]['multiple'])) name="{{ $c }}[]" multiple @else name="{{ $c }}" @endif @if(isset($f[$c]['disabled']) and $f[$c]['disabled'] == true){{ 'disabled' }}@endif @if(isset($f[$c]['required']) and $f[$c]['required'] == true){{ 'required' }}@endif>
                                            <option value="0">---</option>
                                            @if($f[$c]['data'] and is_array($f[$c]['data']))
                                                @foreach($f[$c]['data'] as $k => $v)
                                                    @php isset($f[$c]['key']) ? $value = $k : $value = $v; @endphp
                                                    @if(isset($f[$c]['multiple']))
                                                        <option value="{{ $value }}" @if(isset($row->{$c}) and in_array($value, explode(",", $row->{$c}))){{ 'selected' }}@endif>@if($value != $v){{ $value . '.' }}@endif {{ $v }}</option>
                                                    @else
                                                        <option value="{{ $value }}" @if(isset($row->{$c}) and $row->{$c} == $value){{ 'selected' }}@endif>@if($value != $v){{ $value . '.' }}@endif {{ $v }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @if(isset($f[$c]['description']) and $f[$c]['description'] != '')
                                            <small id="emailHelp" class="form-text text-muted">{{ $f[$c]['description'] }}</small>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            @elseif(isset($f[$c]['type']) and in_array($f[$c]['type'], ['include']))
                                @if(isset($row) and count($row->{$c}()->get()) > 0)
                                    @php $include = $row->{$c}()->get() @endphp
                                    @php $string = substr($c, 0, -1) @endphp
                                    @foreach($include as $v)
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <label class="form-check-label" for="{{ $c }}">@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ ucfirst($c) }}@endif:</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    @if(isset($f[$c]['media']) and $f[$c]['media'] == true)
                                                        <div class="input-group-prepend filemanager" data-src="/fastleo/filemanager?field={{ $c }}{{ $v->id }}">
                                                            <div class="input-group-text"><i class="fas fa-folder-open"></i></div>
                                                        </div>
                                                    @endif
                                                    <input type="text" name="{{ $c }}[{{ $v->id }}]" id="{{ $c }}{{ $v->id }}" data-name="{{ $c }}" class="form-control" placeholder="@if(isset($f[$c]['placeholder'])){{ $f[$c]['placeholder'] }}@else{{ $t }}@endif" value="{{ $v->{$string} }}" @if(isset($f[$c]['disabled']) and $f[$c]['disabled'] == true){{ 'disabled' }}@endif @if(isset($f[$c]['required']) and $f[$c]['required'] == true){{ 'required' }}@endif>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text addInput">+</span>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text delInput">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <label class="form-check-label" for="{{ $c }}">@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ ucfirst($c) }}@endif:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                @if(isset($f[$c]['media']) and $f[$c]['media'] == true)
                                                    <div class="input-group-prepend filemanager" data-src="/fastleo/filemanager?field={{ $c }}1">
                                                        <div class="input-group-text"><i class="fas fa-folder-open"></i></div>
                                                    </div>
                                                @endif
                                                <input type="text" name="{{ $c }}[1]" id="{{ $c }}1" data-name="{{ $c }}" class="form-control" placeholder="@if(isset($f[$c]['placeholder'])){{ $f[$c]['placeholder'] }}@else{{ $t }}@endif" value="" @if(isset($f[$c]['disabled']) and $f[$c]['disabled'] == true){{ 'disabled' }}@endif @if(isset($f[$c]['required']) and $f[$c]['required'] == true){{ 'required' }}@endif>
                                                <div class="input-group-append">
                                                    <span class="input-group-text addInput">+</span>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text delInput">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($f[$c]['description']) and $f[$c]['description'] != '')
                                    <small id="emailHelp" class="form-text text-muted">{{ $f[$c]['description'] }}</small>
                                @endif
                                <hr>
                            @else
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="{{ $c }}">@if(isset($f[$c]['title'])){{ $f[$c]['title'] }}@else{{ ucfirst($c) }}@endif:</label>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            @if(isset($f[$c]['media']) and $f[$c]['media'] == true)
                                                <div class="input-group-prepend filemanager" data-src="/fastleo/filemanager?field={{ $c }}">
                                                    <div class="input-group-text"><i class="fas fa-folder-open"></i></div>
                                                </div>
                                            @endif
                                            <input type="text" name="{{ $c }}" id="{{ $c }}" class="form-control" placeholder="@if(isset($f[$c]['placeholder'])){{ $f[$c]['placeholder'] }}@else{{ $t }}@endif" value="@if(isset($row->{$c})){{ $row->{$c} }}@endif" @if(isset($f[$c]['disabled']) and $f[$c]['disabled'] == true){{ 'disabled' }}@endif @if(isset($f[$c]['required']) and $f[$c]['required'] == true){{ 'required' }}@endif>
                                        </div>
                                        @if(isset($f[$c]['description']) and $f[$c]['description'] != '')
                                            <small id="emailHelp" class="form-text text-muted">{{ $f[$c]['description'] }}</small>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endif
                    @endforeach
                @endif
                <input type="submit" class="btn btn-success" value="Сохранить">
                <input type="submit" name="id" class="btn btn-warning" value="Сохранить и выйти">
                <a href="/fastleo/app/{{ $model_name }}?{{ request()->getQueryString() }}" class="btn btn-primary">Вернуться</a>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            &nbsp;<br><br>
        </div>
    </div>

@endsection
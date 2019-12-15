@extends('fastleo::app')

@section('content')

    <form action="" method="post">

        {{ csrf_field() }}

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="watermark">Водяной знак:</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-prepend filemanager" data-src="/fastleo/filemanager?field=watermark">
                        <div class="input-group-text"><i class="fas fa-folder-open"></i></div>
                    </div>
                    <input type="text" id="watermark" name="watermark" class="form-control" placeholder="ссылка на водяной знак или текст водяного знака" value="{{ $setting['watermark'] ?? '' }}">
                    @if(isset($setting['watermark']) and $setting['watermark'] != '')
                        <div class="input-group-append tt" data-html="true" title="<img src='{{ $setting['watermark'] ?? '' }}' width='182'>">
                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="save"></label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="submit" id="save" class="btn btn-success" value="Сохранить">
                </div>
            </div>
        </div>

    </form>

@endsection
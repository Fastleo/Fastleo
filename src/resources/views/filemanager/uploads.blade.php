@extends('fastleo::filemanager')

@section('content')

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            {{ csrf_field() }}
            <label for="files">Выберите файлы для загрузки</label>
            <input type="file" name="files[]" class="form-control-file" id="files" multiple>
            <button type="submit" class="btn btn-primary">Загрузить</button>
        </div>
    </form>

@endsection
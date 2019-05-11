@extends('fastleo::filemanager')

@section('content')

    <form action="" method="post">
        <div class="form-group">
            {{ csrf_field() }}
            <label for="files">Введите название папки на английском языке</label>
            <input type="text" name="folder_name" class="form-control col-md-6" placeholder="название на английском языке, в качестве пробела используйте нижний пробел _">
            <button type="submit" class="btn btn-primary">Создать папку</button>
        </div>
    </form>

@endsection
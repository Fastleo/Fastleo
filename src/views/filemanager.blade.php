<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fastleo Filemanager</title>
    <script src="//code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="{{ asset('storage/fastleo/css/filemanager.css') }}">
</head>
<body>
@if(session()->has('fastleo'))
    <nav class="navbar navbar-light navbar-dark bg-dark flex-md-nowrap fastleo-nav">
        <a href="{{ route('fastleo.filemanager') }}">Fastleo Filemanager /storage{{ substr(session()->get('folder') ?? '', 6) }}</a>
        <div class="pull-right">
            <span class="trash" style="display: none;"><a href="">Удалить</a> /</span>
            <a href="{{ route('fastleo.filemanager.preview') }}?folder={{ request()->get('folder') ?? '' }}&field={{ request()->get('field') ?? '' }}">Обновить превью</a> /
            <a href="#" data-toggle="modal" data-target="#upload">Загрузить файл</a> /
            <a href="#" data-toggle="modal" data-target="#create">Создать папку</a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="modal fade" id="upload" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('fastleo.filemanager.uploads') }}?folder={{ request()->get('folder') ?? '' }}&field={{ request()->get('field') ?? '' }}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            {{ csrf_field() }}
                            <label for="files">Выберите файлы для загрузки</label>
                            <div class="row">
                                <div class="col-8">
                                    <input type="file" name="files[]" class="form-control-file" id="files" multiple>
                                    <input type="checkbox" name="resize" value="1"> <span>ограничить размер файла 1024px</span><br>
                                    <input type="checkbox" name="watermark" value="1"> <span>добавить водяной знак</span>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary">Загрузить</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('fastleo.filemanager.create') }}?folder={{ request()->get('folder') ?? '' }}&field={{ request()->get('field') ?? '' }}" method="post">
                        <div class="form-group">
                            {{ csrf_field() }}
                            <label for="files">Введите название папки на английском языке, в качестве пробела используйте нижний пробел _</label>
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" name="folder_name" class="form-control" placeholder="folder">
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="form-control btn btn-primary">Создать папку</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endif
</body>
</html>

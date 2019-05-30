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
<nav class="navbar navbar-light navbar-dark bg-dark flex-md-nowrap fastleo-nav">
    <a href="{{ route('fastleo.filemanager') }}" class="navbar-brand">Fastleo Filemanager</a>
    <div class="pull-center">
        /storage{{ substr(session()->get('folder') ?? '', 6) }}
    </div>
    <div class="pull-right">
        <a href="{{ route('fastleo.filemanager.uploads') }}?folder={{ request()->get('folder') ?? '' }}&field={{ request()->get('field') ?? '' }}">Загрузить файл</a> /
        <a href="{{ route('fastleo.filemanager.create') }}?folder={{ request()->get('folder') ?? '' }}&field={{ request()->get('field') ?? '' }}">Создать папку</a>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            @yield('content')
        </div>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
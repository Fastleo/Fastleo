<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fastleo Admin Panel</title>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('storage/fastleo/css/admin.css') }}">
</head>
<body>
<nav class="navbar navbar-light navbar-dark bg-dark flex-md-nowrap fastleo-nav">
    <a class="navbar-brand" href="{{ route('fastleo.info') }}">Fastleo Admin Panel</a>
    @if(session()->has('fastleo'))
        <div class="pull-right">
            <a href="#" class="filemanager" data-src="/fastleo/filemanager">Файловый менеджер</a> /
            <a href="/" target="_blank">Перейти на сайт</a> /
            <a href="{{ route('fastleo.logout') }}">Выйти</a>
        </div>
    @endif
</nav>
<div class="container-fluid fastleo-container">
    <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 bg-light fastleo-menu">
            <ul class="nav flex-column">
                @if(session()->has('fastleo'))
                    <li class="nav-item">
                        <a href="{{ route('fastleo.info') }}" class="nav-link {{ request()->is('fastleo/info') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Информация
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('fastleo.users') }}" class="nav-link {{ request()->is('fastleo/users') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Пользователи
                        </a>
                    </li>
                    @foreach(app()->menu as $title => $model)
                        @if(is_array($model))
                            <li class="nav-item dropdown">
                                <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bars"></i> {{ $title }}
                                </a>
                                <div class="dropdown-menu">
                                    @foreach($model as $t => $m)
                                        <a class="nav-link {{ request()->is('fastleo/app/'. $m) ? 'active' : '' }}" href="/fastleo/app/{{ $m }}">
                                            <i class="fas fa-list"></i> {{ $t }}
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('fastleo/app/'. $model) ? 'active' : '' }}" href="/fastleo/app/{{ $model }}">
                                    <i class="fas fa-list"></i> {{ $title }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                    <li class="nav-item">
                        <a href="{{ route('fastleo.setting') }}" class="nav-link {{ request()->is('fastleo/setting') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i> Настройки
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <br>
                    <a href="https://softonline.org" target="_blank">
                        <small>Softonline</small>
                    </a>
                    <br>
                    <a href="https://github.com/fastleo/fastleo" target="_blank">
                        <small>Github</small>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-lg-10 col-md-9 col-sm-8 fastleo-content">
            @if(session()->has('fastleo'))
                @yield('content')
            @else
                <form action="{{ route('fastleo.login') }}" method="post" class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            @endif
        </div>
    </div>
</div>

<script src="//code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.6/tinymce.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script>
    function filemanager(field, value) {
        $('#' + field).val(value);
    }

    tinymce.init({
        language_url: '{{ asset('storage/fastleo/js/tinymce_lang/ru.js') }}',
        selector: 'textarea.tinymce',
        theme: 'modern',
        plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help code',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor removeformat code | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link image media | fontselect fontsizeselect',
        image_advtab: true,
        relative_urls: false,
        height: 300,
        file_browser_callback: function (field_name, url, type, win) {
            tinyMCE.activeEditor.windowManager.open({
                url: "/fastleo/filemanager",
                width: 1020,
                height: 600,
            }, {
                window: win,
                input: field_name
            });
        }
    });
    $(document).ready(function () {

        $('.tt').tooltip();

        $('.filemanager').on('click', function () {
            var url = $(this).attr('data-src');
            var w = 1020;
            var h = 640;
            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 2);
            return window.open(url, 'filemanager', 'width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        });

        $('.select2').select2();

        $('#import').click(function () {
            $('input[name=import]').trigger('click');
            return false;
        });

        $('input[name=import]').on('change', function () {
            $('#form').submit();
            return false;
        });

        $('.addInput').on('click', function (e) {
            e.preventDefault();

            let div = $(this).closest('.include');
            let divCopy = div.clone(true);

            divCopy.find('input, textarea').val('');
            divCopy.find('.delInput').attr('data-id', '');
            div.after(divCopy);

            let include = $('.include');

            include.each(function (index, value) {
                $(this).find('input, select, textarea').each(function (i, v) {
                    let input = $(this).attr('name');
                    $(this).attr('name', input.replace(/\d+/g, index));
                });
            });

            include.find('input').each(function (index, value) {
                let id = $(this).attr('id');
                let filemanager = $(this).prev('.filemanager').attr('data-src');
                if (id) {
                    $(this).attr('id', id.replace(/\d+/g, index));
                }
                if (filemanager) {
                    $(this).prev('.filemanager').attr('data-src', filemanager.replace(/\d+/g, index));
                }
            });

            return false;
        });

        $('.delInput').on('click', function (e) {
            e.preventDefault();

            let id = $(this).attr('data-id');
            let model = $(this).attr('data-model');
            let div = $(this).closest('.include');
            let include = $('.include').length;

            if (include > 1) {
                if (id > 0) {
                    div.hide(500, function () {
                        $.get('/fastleo/app/' + model + '/delete/' + id + '/true', function () {
                            div.remove();
                        });
                    });
                } else {
                    div.remove();
                }
            }
            return false;
        });

    });
</script>
</body>
</html>
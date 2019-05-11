<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fastleo Admin Panel</title>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('fastleo/css/admin.css') }}">
</head>
<body>
<nav class="navbar navbar-light navbar-dark bg-dark flex-md-nowrap fastleo-nav">
    <a class="navbar-brand" href="{{ route('fastleo.info') }}">Fastleo Admin Panel</a>
    @if(session()->has('admin'))
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
                @if(session()->has('admin'))
                    <li class="nav-item">
                        <a href="{{ route('fastleo.info') }}" class="nav-link"><i class="fas fa-home"></i> Информация</a>
                    </li>
                    @if(isset(request()->appmodels) and count(request()->appmodels) > 0)
                        @foreach(request()->appmodels as $model)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('fastleo/app/'. strtolower($model['name'])) ? 'active' : '' }}" href="/fastleo/app/{{ strtolower($model['name']) }}">
                                    @if(isset($model['icon']) and $model['icon'] != '')
                                        <i class="{{ $model['icon'] }}"></i>
                                    @else
                                        <i class="fas fa-box-open"></i>
                                    @endif
                                    {{ $model['title'] }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('fastleo.log') }}" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Laravel log</a>
                    </li>
                @endif
                <li class="nav-item">
                    <br>
                    <a href="https://softonline.org" target="_blank">
                        <small>Softonline</small>
                    </a>
                    <br>
                    <a href="https://github.com/Fastleo/Fastleo" target="_blank">
                        <small>Github</small>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-lg-10 col-md-9 col-sm-8 fastleo-content">
            @yield('content')
        </div>
    </div>
</div>

<script src="//code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"></script>
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.4/tinymce.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script>
    function filemanager(field, value) {
        $('#' + field).val(value);
    }

    tinymce.init({
        selector: 'textarea.tinymce',
        theme: 'modern',
        plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help code',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor removeformat | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | link image media | code',
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
            var warning = "ВНИМАНИЕ!\n\n" +
                "Проверьте, что файл сохранен в формате CSV UTF-8 (разделитель - запятая)\n" +
                "Используйте для этого Office 2016\n\n" +
                "Все данные будут перезаписаны данными из файла " + $(this).val();
            if (confirm(warning)) {
                $('#form').submit();
            } else {
                window.location.reload();
                return false;
            }
        });

        $('.addInput').on('click', function () {
            var div = $(this).closest('div.row');
            var name = div.find('input').attr('data-name');
            var elements = $('input[data-name=' + name + ']').length;
            var divCopy = div.clone(true);
            divCopy.find('input').val('');
            divCopy.find('input').attr('id', divCopy.find('input').attr('id').replace(/\d+/g, parseInt(elements + 1)));
            divCopy.find('input').attr('name', divCopy.find('input').attr('name').replace(/\d+/g, parseInt(elements + 1)));
            if (divCopy.find('.filemanager').length > 0) {
                divCopy.find('.filemanager').attr('data-src', divCopy.find('.filemanager').attr('data-src').replace(/field=(\w+)/g, 'field=' + name + parseInt(elements + 1)));
            }
            div.after(divCopy);
            return false;
        });

        $('.delInput').on('click', function () {
            var div = $(this).closest('div.row');
            var name = div.find('input').attr('data-name');
            var elements = $('input[data-name=' + name + ']').length;
            if (elements > 1) {
                div.remove();
            }
            return false;
        });
    });
</script>
</body>
</html>
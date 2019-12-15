@extends('fastleo::filemanager')

@section('content')

    <div class="row">

        @if($up != 'public')
            <a href="?folder={{ $up }}&field={{ request()->get('field') }}">
                <div class="block" style="background-image: url({{ asset('storage/fastleo/ico/folder.png') }});">
                    <span class="filename">..</span>
                </div>
            </a>
        @endif

        @foreach($folders as $folder)
            <a href="?folder={{ $folder }}&field={{ request()->get('field') }}" title="{{ $folder }}" class="position-relative">
                <div class="block" style="background-image: url({{ asset('storage/fastleo/ico/folder.png') }});">
                    <span class="filename">{{ \Fastleo\Fastleo\Helper::getName($folder) }}</span>
                </div>
                <input type="checkbox" class="checkbox" value="{{ $folder }}">
            </a>
        @endforeach

        @foreach($files as $file)
            <a href="" class="position-relative">
                <div class="block image" style="background-image: url({{ asset($file['preview']) }}); cursor: pointer;" title="{{ $file['filename'] }}" data-url="/storage{{ substr($file['filename'], 6) }}">
                    <span class="filename">
                        {{ Illuminate\Support\Str::limit(\Fastleo\Fastleo\Helper::getName($file['filename']), 14) }}
                    </span>
                </div>
                <input type="checkbox" class="checkbox" value="{{ $file['filename'] }}">
            </a>
        @endforeach

    </div>

    @if(request()->get('field'))
        <script type="text/javascript">
            $('.image').on('click', function () {
                window.opener.filemanager('{{ request()->get('field') }}', $(this).attr('data-url'));
                window.self.close();
            });
        </script>
    @else
        <script type="text/javascript">
            $(document).ready(function () {
                $('div.image').on('click', function () {
                    var args = top.tinymce.activeEditor.windowManager.getParams();
                    win = (args.window);
                    input = (args.input);
                    win.document.getElementById(input).value = $(this).attr('data-url');
                    top.tinymce.activeEditor.windowManager.close();
                });
            });
        </script>
    @endif

    <script>
        var files = [];
        $('.checkbox').on('change', function () {
            files = [];
            $('.checkbox:checked').each(function (i) {
                files.push($(this).val());
            });
            $('.trash').hide();
            if (files.length > 0) {
                $('.trash').show();
            }
        });
        $('.trash').on('click', function (e) {
            e.preventDefault();
            $.get('/fastleo/filemanager/trash', {files: files}, function () {
                window.location.reload(false);
            })
        });
    </script>

@endsection
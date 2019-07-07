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
            <a href="?folder={{ $folder }}&field={{ request()->get('field') }}" title="{{ $folder }}">
                <div class="block" style="background-image: url({{ asset('storage/fastleo/ico/folder.png') }});">
                    <span class="filename">{{ \Fastleo\Fastleo\Helper::getName($folder) }}</span>
                </div>
            </a>
        @endforeach

        @foreach($files as $file)
            <a href="">
                <div class="block image" style="background-image: url({{ asset($file['preview']) }});" title="{{ $file['filename'] }}" data-url="/storage{{ substr($file['filename'], 6) }}">
                    <span class="filename">{{ Illuminate\Support\Str::limit(\Fastleo\Fastleo\Helper::getName($file['filename']), 14) }}</span>
                </div>
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

@endsection
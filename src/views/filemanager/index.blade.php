@extends('fastleo::filemanager')

@section('content')

    <div class="row">

        <a href="?folder=&field={{ request()->input('field') }}">
            <div class="block" style="background-image: url('/storage/fastleo/ico/folder.png');">
                <span class="filename">..</span>
            </div>
        </a>

        @foreach($folders as $folder)
            <a href="?folder=&field={{ request()->input('field') }}">
                <div class="block" style="background-image: url('/storage/fastleo/ico/folder.png');">
                    <span class="filename">{{ $folder }}</span>
                </div>
            </a>
        @endforeach

        @foreach($files as $file)
            <a href="">
                <div class="block image" style="background-image: url('/ico/jpg.jpg');" data-url="{{ $file['url'] }}">
                        <span class="filename">
                            {{ $file['name'] }}
                        </span>
                </div>
            </a>
        @endforeach
        
    </div>

    @if(request()->input('field'))
        <script type="text/javascript">
            $('.image').on('click', function () {
                window.opener.filemanager('{{ request()->input('field') }}', $(this).attr('data-url'));
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
@extends('fastleo::filemanager')

@section('content')

    <div class="row">
        @if(request()->input('folder'))
            @php $getfolder = explode('/', request()->input('folder')); @endphp
            @php array_pop($getfolder); @endphp
            @php $getfolder = implode('/', $getfolder); @endphp
            <a href="?folder={{ $getfolder }}&field={{ request()->input('field') }}">
                <div class="block" style="background-image: url('/ico/folder.png');">
                    <span class="filename">..</span>
                </div>
            </a>
        @endif
        @if(isset($folders) and count($folders) > 0)
            @foreach($folders as $folder)
                <a href="?folder=@if(request()->input('folder')){{ request()->input('folder') }}@endif/{{ $folder }}&field={{ request()->input('field') }}">
                    <div class="block" style="background-image: url('/ico/folder.png');">
                        <span class="filename">{{ $folder }}</span>
                    </div>
                </a>
            @endforeach
        @endif
        @if(isset($files) and count($files) > 0)
            @foreach($files as $file)
                @php
                    if(in_array(strtolower($file['ext']), $images) and $file['thumbs'] != '') {
                        $background = $file['thumbs'];
                    } else {
                        $background = '/ico/'.$file['ext'].'.jpg';
                    }
                @endphp
                <a href="">
                    <div class="block image" style="background-image: url('{{ $background }}');" data-url="{{ $file['url'] }}">
                        <span class="filename">
                            @php
                                $len = strlen($file['name']);
                            @endphp
                            @if($len > 34)
                                {{ str_limit($file['name'], 15, '...') }}{{ substr($file['name'], -18) }}
                            @else
                                {{ $file['name'] }}
                            @endif
                        </span>
                    </div>
                </a>
            @endforeach
        @endif
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
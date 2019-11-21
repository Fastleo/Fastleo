<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="{{ $column }}">{{ $data['title'] ?? ucfirst($column) }}:</label>
    <div class="col-sm-9">
        <div class="input-group">
            @if($data['media'] ?? false)
                <div class="input-group-prepend filemanager" data-src="/fastleo/filemanager?field={{ $column }}">
                    <div class="input-group-text"><i class="fas fa-folder-open"></i></div>
                </div>
            @endif
            <input type="text" id="{{ $column }}" name="{{ $column }}" class="form-control" placeholder="{{ $data['placeholder'] ?? '' }}" value="{{ $row ? $row->{$column} : '' }}">
        </div>
    </div>
</div>
<hr>
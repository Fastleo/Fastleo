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
            @if(isset($data['media']) and $data['media'] == true and isset($row->{$column}) and $row->{$column} != '')
                <div class="input-group-append tt" data-html="true" title="<img src='{{ $row->{$column} ?? '' }}' width='182'>">
                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                </div>
            @endif
        </div>
        <div><small>{{ $data['description'] ?? '' }}</small></div>
    </div>
</div>
<hr>
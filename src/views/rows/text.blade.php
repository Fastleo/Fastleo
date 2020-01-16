<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="{{ $column }}">{{ $data['title'] ?? ucfirst($column) }}:</label>
    <div class="col-sm-9">
        <textarea name="{{ $column }}" id="{{ $column }}" class="form-control @if(isset($data['tinymce']) and $data['tinymce'] == true){{ 'tinymce' }}@endif" rows="10" placeholder="{{ $relation['placeholder'] ?? '' }}">{{ $row ? $row->{$column} : '' }}</textarea>
    </div>
    <div><small>{{ $data['description'] ?? '' }}</small></div>
</div>
<hr>
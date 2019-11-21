<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="{{ $column }}">{{ $data['title'] ?? ucfirst($column) }}:</label>
    <div class="col-sm-9">
        <textarea name="{{ $column }}" id="{{ $column }}" class="form-control {{ isset($data['tinymce']) ? 'tinymce' : '' }}" rows="10">{{ $row ? $row->{$column} : '' }}</textarea>
    </div>
</div>
<hr>
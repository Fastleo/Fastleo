<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="{{ $column }}">{{ $data['title'] ?? ucfirst($column) }}:</label>
    <div class="col-sm-9">
        <div class="form-check">
            <input type="hidden" name="{{ $column }}" value="0">
            <input type="checkbox" name="{{ $column }}" class="form-check-input" id="{{ $column }}" value="1" {{ $row->{$column} ? 'checked' : null }}>
        </div>
    </div>
</div>
<hr>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="{{ $column }}">{{ $data['title'] ?? ucfirst($column) }}:</label>
    <div class="col-sm-2">
        <div class="input-group">
            <input type="number" name="{{ $column }}" id="{{ $column }}" class="form-control" placeholder="{{ $data['placeholder'] ?? '' }}" value="{{ $row ? $row->{$column} : '' }}">
        </div>
    </div>
</div>
<hr>
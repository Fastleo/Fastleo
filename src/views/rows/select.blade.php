<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="{{ $column }}">{{ $data['title'] ?? ucfirst($column) }}:</label>
    <div class="col-sm-9">
        <select class="form-control col-6 select2" id="{{ $column }}" {!! !empty($data['multiple']) ? 'name="'.$column.'[]" multiple' : 'name="'.$column.'"'!!}>
            <option value="">---</option>
            @if($data['data'])
                @if(!is_array($data['data']))
                    @php $data['data'] = \Fastleo\Fastleo\Helper::str2data($data['data']); @endphp
                @endif
                @foreach($data['data'] as $k => $v)
                    @if(isset($data['multiple']))
                        <option value="{{ $k }}" @if(isset($row->{$column}) and in_array($k, explode(",", $row->{$column}))){{ 'selected' }}@endif>{{ $v }}</option>
                    @else
                        <option value="{{ $k }}" @if(isset($row->{$column}) and $row->{$column} == $k){{ 'selected' }}@endif>{{ $v }}</option>
                    @endif
                @endforeach
            @endif
        </select>
        <div><small>{{ $data['description'] ?? '' }}</small></div>
    </div>
</div>
<hr>
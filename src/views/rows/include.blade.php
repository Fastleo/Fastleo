@php
    $include = isset($row) ? $row->{$data['relation']}()->get() : collect([0 => []]);
    if ($include->count() == 0) {
        $include = collect([0 => []]);
    }
    $relations = \Fastleo\Fastleo\Helper::getModelColumns($data['model']);
    $i = 0;
@endphp

<div class="form-group row">
    <div class="col">
        <h4>{{ $data['title'] ?? ucfirst($column) }}</h4>
    </div>
</div>

@foreach($include as $v)
    @php $iteration = $loop->index; $j = 0 @endphp
    <div class="include">
        @foreach($relations as $col => $relation)
            @if($j == 0)<input type="hidden" name="{{ $column }}[{{ $iteration }}][id]" value="{{ $v->id ?? '' }}">@endif
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ $relation['title'] ?? $data['title'] ?? ucfirst($column) }}:</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-prepend filemanager @if(!isset($relation['media'])){{ 'd-none' }}@endif" data-src="/fastleo/filemanager?field={{ $col }}{{ $i }}">
                            <div class="input-group-text">
                                <i class="fas fa-folder-open"></i>
                            </div>
                        </div>
                        @if(isset($relation['type']) and $relation['type'] == 'text')
                            <textarea name="{{ $column }}[{{ $iteration }}][{{ $col }}]" id="{{ $col }}{{ $i }}" class="form-control" rows="3" placeholder="{{ $relation['placeholder'] ?? '' }}">{{ $v->{$col} ?? '' }}</textarea>
                        @elseif(isset($relation['type']) and $relation['type'] == 'integer')
                            <input type="number" name="{{ $column }}[{{ $iteration }}][{{ $col }}]" id="{{ $col }}{{ $i }}" class="form-control col-sm-2" placeholder="{{ $relation['placeholder'] ?? '' }}" value="{{ $v->{$col} ?? '' }}">
                        @elseif(isset($relation['type']) and $relation['type'] == 'checkbox')
                            <div class="form-check">
                                <input type="hidden" name="{{ $column }}[{{ $iteration }}][{{ $col }}]" value="0">
                                <input type="checkbox" name="{{ $column }}[{{ $iteration }}][{{ $col }}]" class="form-check-input" id="{{ $col }}{{ $i }}" value="1" {{ (isset($v->{$col}) and $v->{$col} == 1) ? 'checked' : null }}>
                            </div>
                        @elseif(isset($relation['type']) and ($relation['type'] == 'select' or $relation['type'] == 'multiselect'))
                            <select class="form-control" id="{{ $col }}{{ $i }}" name="{{ $column }}[{{ $iteration }}][{{ $col }}]">
                                <option value="">---</option>
                                @if($relation['data'])
                                    @if(!is_array($relation['data']))
                                        @php $relation['data'] = \Fastleo\Fastleo\Helper::str2data($relation['data']); @endphp
                                    @endif
                                    @foreach($relation['data'] as $ok => $ov)
                                        <option value="{{ $ok }}" @if(isset($v->{$col}) and $v->{$col} == $ok){{ 'selected' }}@endif>{{ $ov }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @else
                            <input type="{{ $relation['type'] ?? 'text' }}" name="{{ $column }}[{{ $iteration }}][{{ $col }}]" id="{{ $col }}{{ $i }}" data-name="{{ $col }}" class="form-control" placeholder="{{ $relation['placeholder'] ?? '' }}" value="{{ $v->{$col} ?? '' }}">
                            @if(isset($relation['media']) and isset($v->{$col}) and $v->{$col} != '')
                                <div class="input-group-append tt" data-html="true" title="<img src='{{ $v->{$col} ?? '' }}' width='182'>">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div><small>{{ $data['description'] ?? '' }}</small></div>
                </div>
            </div>
            @php $i++; $j++; @endphp
        @endforeach
        <div class="col-sm-7 offset-sm-2 text-right">
            <a href="" class="addInput">добавить ещё</a> /
            <a href="" class="delInput" data-model="{{ class_basename($data['model']) }}" data-id="{{ $v->id ?? '' }}">удалить</a>
        </div>
        <hr>
    </div>
@endforeach
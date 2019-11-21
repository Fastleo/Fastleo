@php
    if(isset($row)) {
        $include = $row->{\Fastleo\Fastleo\Helper::str2class($column)}()->get();
    }
    if(!isset($row) or $include->count() == 0) {
        $include = collect([0 => []]);
    }
    $relations = \Fastleo\Fastleo\Helper::str2model($column);
@endphp
@if($include->count() > 0)
    @foreach($include as $v)
        @php $iteration = $loop->index; @endphp
        <div class="include">
            @foreach($relations as $col => $relation)
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">{{ $relation['title'] ?? $data['title'] ?? ucfirst($column) }}:</label>
                    <div class="col-sm-7">
                        <div class="input-group">
                            @if(isset($relation['media']) and $relation['media'] == true)
                                <div class="input-group-prepend filemanager" data-src="/fastleo/filemanager?field={{ $col }}{{ $loop->index }}">
                                    <div class="input-group-text"><i class="fas fa-folder-open"></i></div>
                                </div>
                            @endif
                            <input type="text" name="{{ $column }}[{{ $iteration }}][{{ $col }}]" id="{{ $col }}{{ $loop->index }}" data-name="{{ $col }}" class="form-control" placeholder="{{ $relation['placeholder'] ?? '' }}" value="{{ $v->{substr($col, 0, -1)} ?? $v->{$col} ?? '' }}">
                            <div class="input-group-append">
                                <span class="input-group-text addInput">+</span>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text delInput">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endif
<hr>
@php
    $relationshipDetails = $relationship['details'];
    $relationshipKeyArray = array_fill_keys(
        ["model", "table", "type", "column", "key", "label", "pivot_table", "pivot", "taggable"],
        ''
    );

    $adv_details = array_diff_key(
        json_decode(json_encode($relationshipDetails), true),
        $relationshipKeyArray
    );
@endphp
<div class="row row-dd row-dd-relationship">
    <div class="col-2 position-relative">
        <h4>
            <i class="hymer-heart"></i>
            <strong>{{ $relationship->getTranslatedAttribute('display_name') }}</strong>
        </h4>
        <div class="handler hymer-handle"></div>
        <strong>{{ __('hymer::database.type') }}:</strong>
        <span>{{ __('hymer::database.relationship.relationship') }}</span>
        <br/>
        <strong>{{ __('hymer::generic.required') }}:</strong>
        <input
            type="checkbox" value="1"
            name="field_required_{{ $relationship['field'] }}"
            @if(!empty($relationship->required))checked="checked"@endif
        >
        <div class="handler hymer-handle"></div>
        <input
            class="row_order" type="hidden" value="{{ $relationship['order'] }}"
            name="field_order_{{ $relationship['field'] }}">
    </div>
    <div class="col-2">
        <input
            type="checkbox" name="field_browse_{{ $relationship['field'] }}"
            @if(isset($relationship->browse) && $relationship->browse)
                checked="checked"
            @elseif(!isset($relationship->browse))
                checked="checked"
            @endif
        >
        <label for="field_browse_{{ $relationship['field'] }}">
            {{ __('hymer::database.relationship.browse') }}
        </label>
        <br>
        <input
            type="checkbox" name="field_read_{{ $relationship['field'] }}"
            @if(isset($relationship->read) && $relationship->read)
                checked="checked"
            @elseif(!isset($relationship->read))
                checked="checked"
            @endif
        >
        <label for="field_read_{{ $relationship['field'] }}">
            {{ __('hymer::database.relationship.read') }}
        </label>
        <br>
        <input
            type="checkbox" name="field_edit_{{ $relationship['field'] }}"
            @if(isset($relationship->edit) && $relationship->edit)
                checked="checked"
            @elseif(!isset($relationship->edit))
                checked="checked"
            @endif>
        <label for="field_edit_{{ $relationship['field'] }}">
            {{ __('hymer::database.relationship.edit') }}
        </label>
        <br>
        <input
            type="checkbox" name="field_add_{{ $relationship['field'] }}"
            @if(isset($relationship->add) && $relationship->add)
                checked="checked"
            @elseif(!isset($relationship->add))
                checked="checked"
            @endif>
        <label for="field_add_{{ $relationship['field'] }}">
            {{ __('hymer::database.relationship.add') }}
        </label>
        <br>
        <input
            type="checkbox" name="field_delete_{{ $relationship['field'] }}"
            @if(isset($relationship->delete) && $relationship->delete)
                checked="checked"
            @elseif(!isset($relationship->delete))
                checked="checked"
            @endif>
        <label for="field_delete_{{ $relationship['field'] }}">
            {{ __('hymer::database.relationship.delete') }}
        </label>
        <br>
    </div>
    <div class="col-2">
        <p>{{ __('hymer::database.relationship.relationship') }}</p>
    </div>
    <div class="col-2">
        @if($isModelTranslatable)
            @include('hymer::multilingual.input-hidden', [
                'isModelTranslatable' => true,
                '_field_name'         => 'field_display_name_' . $relationship['field'],
                '_field_trans' => get_field_translations($relationship, 'display_name')
            ])
        @endif
        <input
            type="text" name="field_display_name_{{ $relationship['field'] }}"
            class="form-control relationship_display_name" value="{{ $relationship['display_name'] }}">
    </div>
    <div class="col-4">
        <div class="hymer-relationship-details-btn">
            <i class="hymer-angle-down"></i><i class="hymer-angle-up"></i>
            <span class="open_text">{{ __('hymer::database.relationship.open') }}</span>
            <span class="close_text">{{ __('hymer::database.relationship.close') }}</span>
            {{ __('hymer::database.relationship.relationship_details') }}
        </div>
    </div>
    <div class="col-md-12 hymer-relationship-details">
        <a href="{{ route('hymer.bread.delete_relationship', $relationship['id']) }}"
           class="delete_relationship">
            <i class="hymer-trash"></i>
            {{ __('hymer::database.relationship.delete') }}
        </a>
        <div class="relationship_details_content">
            <p class="relationship_table_select">{{ Str::singular(ucfirst($table)) }}</p>
            <select class="relationship_type select2" name="relationship_type_{{ $relationship['field'] }}">
                <option
                    value="hasOne"
                    @if(isset($relationshipDetails->type) && $relationshipDetails->type == 'hasOne')
                        selected="selected"
                    @endif
                >
                    {{ __('hymer::database.relationship.has_one') }}
                </option>
                <option
                    value="hasMany"
                    @if(isset($relationshipDetails->type) && $relationshipDetails->type == 'hasMany')
                        selected="selected"
                    @endif
                >
                    {{ __('hymer::database.relationship.has_many') }}
                </option>
                <option
                    value="belongsTo"
                    @if(isset($relationshipDetails->type) && $relationshipDetails->type == 'belongsTo')
                        selected="selected"
                    @endif
                >
                    {{ __('hymer::database.relationship.belongs_to') }}
                </option>
                <option
                    value="belongsToMany"
                    @if(isset($relationshipDetails->type) && $relationshipDetails->type == 'belongsToMany')
                        selected="selected"
                    @endif
                >
                    {{ __('hymer::database.relationship.belongs_to_many') }}
                </option>
            </select>
            <select class="relationship_table select2" name="relationship_table_{{ $relationship['field'] }}">
                @foreach($tables as $tablename)
                    <option
                        value="{{ $tablename }}"
                        @if(isset($relationshipDetails->table) && $relationshipDetails->table == $tablename)
                            selected="selected"
                        @endif
                    >{{ ucfirst($tablename) }}</option>
                @endforeach
            </select>
            <span><input type="text" class="form-control" name="relationship_model_{{ $relationship['field'] }}" placeholder="{{ __('hymer::database.relationship.namespace') }}" value="{{ $relationshipDetails->model ?? '' }}"></span>
        </div>
            <div class="relationshipField">
                <div class="relationship_details_content margin_top belongsTo
                        @if($relationshipDetails->type == 'belongsTo') flexed @endif">
                    <label>
                        {{ __('hymer::database.relationship.which_column_from') }}
                        <span>{{ Str::singular(ucfirst($table)) }}</span>
                        {{ __('hymer::database.relationship.is_used_to_reference') }}
                        <span class="label_table_name"></span>?
                    </label>
                    <select name="relationship_column_belongs_to_{{ $relationship['field'] }}"
                            class="new_relationship_field select2">
                        @foreach($fieldOptions as $data)
                            <option
                                value="{{ $data['field'] }}"
                                @if($relationshipDetails->column == $data['field'])
                                    selected="selected"
                                @endif>{{ $data['field'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relationship_details_content margin_top hasOneMany
                    @if($relationshipDetails->type == 'hasOne' || $relationshipDetails->type == 'hasMany')
                        flexed
                    @endif">
                    <label>
                        {{ __('hymer::database.relationship.which_column_from') }}
                        <span class="label_table_name"></span>
                        {{ __('hymer::database.relationship.is_used_to_reference') }}
                        <span>{{ Str::singular(ucfirst($table)) }}</span>?
                    </label>
                    <select
                        name="relationship_column_{{ $relationship['field'] }}"
                        class="new_relationship_field select2 rowDrop"
                        data-table="{{ $relationshipDetails->table ?? '' }}"
                        data-selected="{{ $relationshipDetails->column }}">
                        <option value="{{ $relationshipDetails->column ?? '' }}">
                            {{ $relationshipDetails->column ?? '' }}
                        </option>
                    </select>
                </div>
            </div>
        <div class="relationship_details_content margin_top relationshipPivot
                @if($relationshipDetails->type == 'belongsToMany') visible @endif">
            <label>{{ __('hymer::database.relationship.pivot_table') }}:</label>
            <select name="relationship_pivot_table_{{ $relationship['field'] }}" class="select2">
                @foreach($tables as $tbl)
                    <option
                        value="{{ $tbl }}"
                        @if(isset($relationshipDetails->pivot_table) && $relationshipDetails->pivot_table == $tbl)
                            selected="selected" @endif
                    >{{ Str::singular(ucfirst($tbl)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="relationship_details_content margin_top">
            <label>{{ __('hymer::database.relationship.display_the') }} <span class="label_table_name"></span></label>
            <select
                name="relationship_label_{{ $relationship['field'] }}"
                class="rowDrop select2" data-table="{{ $relationshipDetails->table ?? '' }}"
                data-selected="{{ $relationshipDetails->label ?? ''}}"
            >
                <option value="{{ $relationshipDetails->label ?? '' }}">
                    {{ $relationshipDetails->label ?? '' }}
                </option>
            </select>
            <div class="belongsToShow belongsToManyShow relationship_details_content" style="flex:1">
                <label class="relationship_key">
                    {{ __('hymer::database.relationship.store_the') }}
                    <span class="label_table_name"></span>
                </label>
                <select
                    name="relationship_key_{{ $relationship['field'] }}"
                    class="rowDrop select2 relationship_key"
                    data-table="@if(isset($relationshipDetails->table)){{ $relationshipDetails->table }}@endif"
                    data-selected="@if(isset($relationshipDetails->key)){{ $relationshipDetails->key }}@endif"
                >
                    <option value="{{ $relationshipDetails->key ?? '' }}">
                        {{ $relationshipDetails->key ?? '' }}
                    </option>
                </select>
            </div>
            <div class="hasOneShow hasManyShow relationship_details_content" style="flex:1">
                <label class="relationship_key">{{ __('hymer::database.relationship.store_the') }}
                    <span>{{ Str::singular(ucfirst($table)) }}</span>
                </label>
                <select name="relationship_key_{{ $relationship['field'] }}" class="select2 relationship_key">
                    @foreach($fieldOptions as $data)
                        <option
                            value="{{ $data['field'] }}"
                            @if($relationshipDetails->key == $data['field'])
                                selected="selected"
                            @endif
                        >{{ $data['field'] }}</option>
                    @endforeach
                </select>
            </div>
            <br>
            @isset($relationshipDetails->taggable)
                <div class="relationship_taggable">
                    <div class="d-flex align-items-center" style="height: 100%">
                        <label for="tableAllowTagging">
                            {{__('hymer::database.relationship.allow_tagging')}}
                        </label>
                        <div class="form-check form-switch d-flex align-items-center">
                            <input
                                class="form-check-input"
                                name="relationship_taggable_{{ $relationship['field'] }}"
                                type="checkbox" id="tableAllowTagging"
                                {{$relationshipDetails->taggable == 'on' ? 'checked' : ''}}
                            >
                        </div>
                    </div>
                </div>
            @endisset
        </div>
        <div class="relationship_details_content margin_top">
            <div class="col-12" style="margin: 0px !important; padding: 0px !important;">
                <div class="alert alert-danger validation-error">
                    {{ __('hymer::json.invalid') }}
                </div>
                <label class="form-label">{{ __('hymer::database.relationship.relationship_details') }}</label>
                <textarea
                    id="json-input-{{ ($relationship['field']) }}"
                    class="resizable-editor" data-editor="json"
                    name="field_details_{{ $relationship['field'] }}"
                >
                    @if(!empty($adv_details))
                        {{ json_encode($adv_details) }}
                    @else
                        {}
                    @endif
                </textarea>
            </div>
        </div>
    </div>
    <input type="hidden" name="field_input_type_{{ $relationship['field'] }}" value="relationship">
    <input type="hidden" name="field_{{ $relationship['field'] }}" value="{{ $relationship['field'] }}">
    <input type="hidden" name="relationships[]" value="{{ $relationship['field'] }}">
</div>

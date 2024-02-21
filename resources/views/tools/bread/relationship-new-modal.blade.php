<!-- !!! Add form action below -->
<div class="modal fade modal-danger modal-relationships" id="new_relationship_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="hymer-heart"></i>
                    <span>{{ Str::singular(ucfirst($table)) }}</span>
                    <span>{{ __('hymer::database.relationship.relationships') }} </span>
                </h4>
                <button type="button" class="btn modal-close" data-bs-dismiss="modal"
                        aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form role="form" action="{{ route('hymer.bread.relationship') }}" method="POST"
                      id="newRelationshipForm">
                    <div id="fixedSelect2"></div>
                    <div class="row">
                        @if(isset($dataType->id))
                            <div class="col-12 relationship_details">
                                <p class="col-1 relationship_table_select">{{ Str::singular(ucfirst($table)) }}</p>
                                <div class="col-3">
                                    <select class="relationship_type select2" name="relationship_type">
                                        <option
                                            value="hasOne"
                                            @if(
                                                isset($relationshipDetails->type) &&
                                                $relationshipDetails->type == 'hasOne'
                                            )
                                                selected="selected"
                                            @endif
                                        >{{ __('hymer::database.relationship.has_one') }}</option>
                                        <option
                                            value="hasMany"
                                            @if(
                                                isset($relationshipDetails->type) &&
                                                $relationshipDetails->type == 'hasMany'
                                            )
                                                selected="selected"
                                            @endif
                                        >{{ __('hymer::database.relationship.has_many') }}</option>
                                        <option
                                            value="belongsTo"
                                            @if(
                                                isset($relationshipDetails->type) && $relationshipDetails->type == 'belongsTo'
                                            )
                                                selected="selected"
                                            @endif
                                        >{{ __('hymer::database.relationship.belongs_to') }}</option>
                                        <option
                                            value="belongsToMany"
                                            @if(
                                                isset($relationshipDetails->type) &&
                                                $relationshipDetails->type == 'belongsToMany'
                                            )
                                                selected="selected"
                                            @endif
                                        >{{ __('hymer::database.relationship.belongs_to_many') }}</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="relationship_table select2" name="relationship_table">
                                        @foreach($tables as $tbl)
                                            <option
                                                value="{{ $tbl }}"
                                                @if(
                                                    isset($relationshipDetails->table) &&
                                                    $relationshipDetails->table == $tbl
                                                )
                                                    selected="selected"
                                                @endif
                                            >{{ Str::singular(ucfirst($tbl)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-5">
                                    <input
                                        type="text" class="form-control" name="relationship_model"
                                        placeholder="{{ __('hymer::database.relationship.namespace') }}"
                                        value="{{ $relationshipDetails->model ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-12 relationship_details relationshipField mt-3">
                                <div class="belongsTo">
                                    <label>
                                        <span>{{ __('hymer::database.relationship.which_column_from') }}</span>
                                        <span>{{ Str::singular(ucfirst($table)) }}</span>
                                        <span>{{ __('hymer::database.relationship.is_used_to_reference') }}</span>
                                        <span class="label_table_name"></span>?
                                    </label>
                                    <select name="relationship_column_belongs_to"
                                            class="new_relationship_field select2">
                                        @foreach($fieldOptions as $data)
                                            <option value="{{ $data['field'] }}">{{ $data['field'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="hasOneMany flexed">
                                    <label>
                                        <span>{{ __('hymer::database.relationship.which_column_from') }}</span>
                                        <span class="label_table_name"></span>
                                        <span>{{ __('hymer::database.relationship.is_used_to_reference') }}</span>
                                        <span>{{ Str::singular(ucfirst($table)) }}</span>?</label>
                                    <select
                                        name="relationship_column"
                                        class="new_relationship_field select2 rowDrop"
                                        data-table="{{ $tables[0] }}" data-selected="">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 relationship_details relationshipPivot mt-3">
                                <label>{{ __('hymer::database.relationship.pivot_table') }}:</label>
                                <select name="relationship_pivot" class="select2">
                                    @foreach($tables as $tbl)
                                        <option
                                            value="{{ $tbl }}"
                                            @if(
                                                isset($relationshipDetails->table) &&
                                                $relationshipDetails->table == $tbl
                                            )
                                                selected="selected"
                                            @endif
                                        >{{ Str::singular(ucfirst($tbl)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 relationship_details_more">
                                <div class="well">
                                    <label>
                                        {{ __('hymer::database.relationship.selection_details') }}
                                    </label>
                                    <p>
                                        <strong>
                                            {{ __('hymer::database.relationship.display_the') }}
                                            <span class="label_table_name"></span>:
                                        </strong>
                                        <select
                                            name="relationship_label" class="rowDrop select2"
                                            data-table="{{ $tables[0] }}" data-selected="" style="width: 100%"
                                        >
                                        </select>
                                    </p>
                                    <p class="relationship_key belongsToShow belongsToManyShow">
                                        <strong>
                                            {{ __('hymer::database.relationship.store_the') }}
                                            <span class="label_table_name"></span>:
                                        </strong>
                                        <select
                                            name="relationship_key" class="rowDrop select2"
                                            data-table="{{ $tables[0] }}" data-selected="" style="width: 100%">
                                        </select>
                                    </p>
                                    <p class="relationship_key hasOneShow hasManyShow">
                                        <strong>
                                            {{ __('hymer::database.relationship.store_the') }}
                                            <span>{{ Str::singular(ucfirst($table)) }}</span>:
                                        </strong>
                                        <select name="relationship_key" class="select2" style="width: 100%">
                                            @foreach($fieldOptions as $data)
                                                <option value="{{ $data['field'] }}">{{ $data['field'] }}</option>
                                            @endforeach
                                        </select>
                                    </p>
                                    <div class="relationship_taggable">
                                        <strong>{{ __('hymer::database.relationship.allow_tagging') }}:</strong>
                                        <br>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="relationship_taggable">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12">
                                <h5>
                                    <i class="hymer-rum-1"></i>
                                    {{ __('hymer::database.relationship.easy_there') }}
                                </h5>
                                <p class="relationship-warn">
                                    {!! __('hymer::database.relationship.before_create') !!}
                                </p>
                            </div>
                        @endif

                    </div>
                    <input type="hidden" value="{{ $dataType->id ?? '' }}" name="data_type_id">
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="modal-footer">
                <div class="relationship-btn-container">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
                        {{ __('hymer::database.relationship.cancel') }}
                    </button>
                    @if(isset($dataType->id))
                        <button class="btn btn-danger btn-relationship" id="submitNewRelationship">
                            <i class="hymer-plus"></i>
                            <span>{{ __('hymer::database.relationship.add_new') }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


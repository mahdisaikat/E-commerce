@extends('backend.layout.master')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    {{ !empty($title) ? $title : ucfirst(explode('.', Route::currentRouteName())[0]) }}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add/Edit Modals --}}
    @if(isset($formFields) && !empty($formFields))
        @foreach(['add', 'edit'] as $action)
            <div class="modal fade" id="{{ $action }}Modal" tabindex="-1" data-coreui-backdrop="static"
                data-coreui-keyboard="false">
                <div class="modal-dialog {{ count($formFields) > 4 ? 'modal-xl' : '' }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                @lang('backend.' . ($action == 'add' ? 'create' : 'edit')) {{ $name }}
                            </h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="{{ $action }}">
                            @csrf
                            @if($action == 'edit') @method('PATCH') @endif
                            <input type="hidden" name="id" class="id">
                            <div class="modal-body">
                                <div class="row">
                                    @foreach($formFields as $index => $field)
                                        <div class="{{ count($formFields) > 4 ? 'col-md-6' : 'col-md-12' }}">
                                            <div class="form-group mb-2">
                                                @if(!empty($field['label']))
                                                    <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                                                @endif
                                                @if($field['type'] == 'textarea')
                                                    <textarea name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror"></textarea>
                                                @elseif($field['type'] == 'select')
                                                    <select name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                        class="form-select select2 {{ $field['name'] }} @error($field['name']) is-invalid @enderror">
                                                        @if(is_array($field['options']) && !empty($field['options']))
                                                            @foreach($field['options'] as $key => $value)
                                                                <option value="{{ $key }}">{{ $value }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="">@lang('backend.no_option')</option>
                                                        @endif
                                                    </select>
                                                @elseif($field['type'] == 'checkbox')
                                                    <div class="form-check">
                                                        <input type="checkbox" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                            value="1"
                                                            class="form-check-input {{ $field['name'] }} @error($field['name']) is-invalid @enderror">
                                                        <label class="form-check-label" for="{{ $field['name'] }}">
                                                            @if(!empty($field['checkbox-label']))
                                                                {{ $field['checkbox-label'] }}
                                                            @endif
                                                        </label>
                                                    </div>
                                                @elseif($field['type'] == 'date')
                                                    <input type="date" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror"
                                                        value="{{ date('Y-m-d') }}">
                                                @elseif($field['type'] == 'number')
                                                    <input type="number" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror">
                                                @elseif($field['type'] == 'default')
                                                    <input type="hidden" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror"
                                                        value="{{ $field['value'] }}">
                                                @else
                                                    <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}"
                                                        id="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror">
                                                @endif
                                                @error($field['name'])
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @if(count($formFields) > 4 && ($index + 1) % 2 == 0)
                                            </div>
                                            <div class="row">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="reset" class="btn btn-warning btn-sm"
                                    data-coreui-dismiss="modal">@lang('backend.close')</button>
                                <button type="submit"
                                    class="btn btn-success btn-sm">{{ $action == 'add' ? __('backend.save') : __('backend.update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- View Modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" data-coreui-backdrop="static" data-coreui-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View {{ $name ?? ucfirst(explode('.', Route::currentRouteName())[0]) }}</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row" id="viewFieldsContainer">
                        <!-- Fields will be dynamically inserted here by JavaScript -->
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        $(document).ready(function () {
            let table = $('#{{ $name }}-datatable').DataTable();
            let baseRoute = "{{ url('/') }}/{{ $route }}";

            // Pass the form field definitions from PHP to JavaScript as a JSON object.
            // This is the key to avoiding the "already declared" error.
            const formFields = @json($formFields ?? []);

            function handleFormSubmit(formId, method, url) {
                $(`#${formId}`).off('submit').on('submit', function (e) {
                    e.preventDefault();
                    let formData = new FormData(this);
                    if (method === 'PATCH') formData.append('_method', 'PATCH');

                    let submitButton = $(`#${formId} button[type="submit"]`);
                    submitButton.attr('disabled', true).text(method === 'POST' ? 'Submitting...' : 'Updating...');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        dataType: 'JSON',
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            submitButton.attr('disabled', false).text(method === 'POST' ? 'Save' : 'Update');
                            if (response.type === 'success') {
                                showToast({
                                    icon: response.type || 'success',
                                    title: response.message || 'Operation successful'
                                });
                                $(`#${formId}Modal`).modal('hide');
                                $(`#${formId}`).trigger("reset");
                                table.ajax.reload(null, false);
                            }
                        },
                        error: function (response) {
                            submitButton.attr('disabled', false).text(method === 'POST' ? 'Save' : 'Update');
                            let errors = response.responseJSON.errors;
                            $('.invalid-feedback').remove();
                            $(`#${formId} input, #${formId} select, #${formId} textarea`).removeClass("is-invalid");
                            for (let field in errors) {
                                let errorMessage = errors[field][0];
                                $(`[name="${field}"]`).addClass("is-invalid").after(`<div class="invalid-feedback">${errorMessage}</div>`);
                            }
                        }
                    });
                });
            }

            handleFormSubmit('add', 'POST', baseRoute);

            window.edit = function (id) {
                $.ajax({
                    type: "GET",
                    url: `${baseRoute}/${id}/edit`,
                    success: function (data) {
                        // Reset previous validation errors
                        $('#edit .invalid-feedback').remove();
                        $('#edit input, #edit select, #edit textarea').removeClass("is-invalid");

                        for (let field in data) {
                            // Handle checkbox state
                            if ($(`.edit .${field}`).is(':checkbox')) {
                                $(`.edit .${field}`).prop('checked', data[field] == 1);
                            } else {
                                $(`.${field}`).val(data[field]);
                            }
                        }
                        handleFormSubmit('edit', 'PATCH', `${baseRoute}/${id}`);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                    }
                });
            };

            window.view = function (id) {
                $.ajax({
                    type: "GET",
                    url: `${baseRoute}/${id}`,
                    success: function (data) {

                        let container = $('#viewFieldsContainer');
                        container.empty();

                        // Render fields from formFields
                        formFields.forEach(field => {
                            if (field.type === 'default') return;

                            const fieldLabel = field.label || field.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            let value = field.name.split('.').reduce((o, i) => o ? o[i] : undefined, data);

                            if (field.type === 'select' && field.options && field.options[value]) {
                                value = field.options[value];
                            } else if (field.type === 'checkbox') {
                                value = value == 1 ? 'Yes' : 'No';
                            } else if (field.name.includes('image') || field.name.includes('avatar')) {
                                if (Array.isArray(data.images) && data.images.length > 0) {
                                    const imagePath = `/storage/images/${data.images[0].type}/${data.images[0].filename}`;
                                    value = `<img src="${imagePath}" alt="${fieldLabel}" style="max-width: 100px; max-height: 100px; border-radius: 5px;">`;
                                } else {
                                    value = '-';
                                }
                            } else {
                                value = (value === null || value === undefined || value === '') ? '-' : value;
                            }

                            container.append(`
                                        <dt class="col-sm-4">${fieldLabel}</dt>
                                        <dd class="col-sm-8">${value}</dd>
                                    `);
                        });

                        if (data.hasOwnProperty('status')) {
                            let statusLabel = data.status == 1
                                ? '<span class="badge bg-success">Active</span>'
                                : '<span class="badge bg-warning">Inactive</span>';

                            container.append(`
                                        <dt class="col-sm-4">Status</dt>
                                        <dd class="col-sm-8">${statusLabel}</dd>
                                    `);
                        }

                        $('#viewModal').modal('show');
                    },
                    error: function (xhr) {
                        console.error('Failed to load view data:', xhr.responseText);
                        showToast({ icon: 'error', title: 'Failed to load data' });
                    }
                });
            };

        });
    </script>
@endpush
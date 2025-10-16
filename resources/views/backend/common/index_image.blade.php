@extends('backend.layout.master')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                {{-- <div class="card-header">
                    {{ !empty($title) ? $title : ucfirst(explode('.', Route::currentRouteName())[0]) }}
                </div> --}}
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($formFields) && !empty($formFields))
        @foreach(['add', 'edit'] as $action)
            <div class="modal fade" id="{{ $action }}Modal" tabindex="-1" data-coreui-backdrop="static"
                data-coreui-keyboard="false">
                <div class="modal-dialog {{ count($formFields) > 4 ? 'modal-xl' : '' }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                @lang('admin_fields.' . ($action == 'add' ? 'create' : 'edit')) {{ $name }}
                            </h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="{{ $action }}">
                            @csrf
                            @if($action == 'edit') @method('PATCH') @endif
                            <input type="hidden" name="id" class="id">
                            <div class="modal-body">
                                <div id="imagePreview" style="width: 500px; height: 500px; display: none;">
                                    <img id="previewImage" src="" alt="Preview">
                                </div>
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
                                                            <option value="">@lang('admin_fields.no_option')</option>
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
                                                @elseif($field['type'] == 'file')
                                                    <input type="file" id="imageInput" name="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror">
                                                @elseif($field['type'] == 'date')
                                                    <input type="date" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror"
                                                        value="{{ date('Y-m-d') }}">
                                                @elseif($field['type'] == 'number')
                                                    <input type="number" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                                        class="form-control {{ $field['name'] }} @error($field['name']) is-invalid @enderror">
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
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-warning btn-sm"
                                    data-coreui-dismiss="modal">@lang('admin_fields.close')</button>
                                <button type="submit" id="uploadBtn" style="display: none;"
                                    class="btn btn-success btn-sm">{{ $action == 'add' ? __('admin_fields.save') : __('admin_fields.update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        $(document).ready(function () {
            let table = $('#{{ $name }}-datatable').DataTable();
            let baseRoute = "{{ url('/') }}/{{ $route }}";

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
                            $(`#${formId} input:not([name='_token'])`).removeClass("is-invalid");
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
                        for (let field in data) {
                            $(`.${field}`).val(data[field]);
                        }
                        handleFormSubmit('edit', 'PATCH', `${baseRoute}/${id}`);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                    }
                });
            };
        });

        document.getElementById('imageInput').addEventListener('change', function (e) {
            if (e.target.files.length === 0) return;

            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function (event) {
                const preview = document.getElementById('previewImage');
                preview.src = event.target.result;

                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('uploadBtn').style.display = 'block';

                // Initialize cropper
                const cropper = new Cropper(preview, {
                    aspectRatio: 1, // Square aspect ratio
                    viewMode: 1,
                    autoCropArea: 0.8,
                    responsive: true,
                    guides: true
                });

                // Handle upload button click
                document.getElementById('uploadBtn').addEventListener('click', function () {
                    // Get cropped canvas
                    cropper.getCroppedCanvas({
                        width: 800,
                        height: 800,
                        minWidth: 256,
                        minHeight: 256,
                        maxWidth: 4096,
                        maxHeight: 4096,
                        fillColor: '#fff',
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    }).toBlob(function (blob) {
                        // Create form data
                        const formData = new FormData();
                        formData.append('image', blob, file.name);
                        formData.append('_token', '{{ csrf_token() }}');

                        // Upload to server
                        fetch('/upload-image', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Upload successful:', data);
                                // Handle success
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }, file.type);
                });
            };

            reader.readAsDataURL(file);
        });
    </script>
@endpush
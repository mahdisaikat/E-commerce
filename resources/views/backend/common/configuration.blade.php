@extends('backend.layout.master')

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
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

    <!--Add modal Start-->
    <div class="modal fade" id="addModal" tabindex="-1" data-coreui-backdrop="static" data-coreui-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('backend.add_new')</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="type">Field Type</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="">Please Select</option>
                                <option value="text">Text</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="file">File</option>
                                <option value="color">Color</option>
                                <option value="number">Number</option>
                                <option value="textarea">Textarea</option>
                                <option value="url">URL</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="key">Key</label>
                            <input id="key" name="key" class="form-control" type="text" autocomplete="off">
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="form-group mb-2">
                            <label for="remarks">Remarks</label>
                            <input id="remarks" name="remarks" class="form-control" type="text" autocomplete="off">
                            @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="form-group mb-2">
                            <label for="value">Value</label>
                            <!-- Input fields for different types -->
                            <input type="text" name="value" id="value"
                                class="form-control @error('value') is-invalid @enderror">
                            <input type="file" name="value_file" id="value_file"
                                class="form-control @error('value') is-invalid @enderror" style="display:none;">
                            <textarea name="value_textarea" id="value_textarea"
                                class="form-control @error('value') is-invalid @enderror" style="display:none;"></textarea>
                            <input type="color" name="value_color" id="value_color"
                                class="form-control form-control-color @error('value') is-invalid @enderror"
                                style="display:none;">
                            <input type="number" name="value_number" id="value_number"
                                class="form-control @error('value') is-invalid @enderror" style="display:none;">
                            <input type="url" name="value_url" id="value_url"
                                class="form-control @error('value') is-invalid @enderror" style="display:none;">
                            <!-- New dropdown field -->
                            <select name="value_dropdown" id="value_dropdown"
                                class="form-control @error('value') is-invalid @enderror" style="display:none;">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="reset" class="btn btn-warning btn-sm"
                            data-coreui-dismiss="modal">@lang('backend.close')</button>
                        <button type="submit" class="btn btn-success btn-sm Save">@lang('backend.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Add modal Ends-->

    <!--Edit modal Start-->
    <div class="modal fade" id="editModal" tabindex="-1" data-coreui-backdrop="static" data-coreui-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('backend.edit')</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updatE" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="modal-body">
                        <input class="id" type="hidden" name="id" />
                        <div class="form-group mb-2">
                            <label for="edit_type">Field Type</label>
                            <select name="type" id="edit_type" class="form-select type @error('type') is-invalid @enderror">
                                <option value="">Please Select</option>
                                <option value="text">Text</option>
                                <option value="file">File</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="color">Color</option>
                                <option value="number">Number</option>
                                <option value="textarea">Textarea</option>
                                <option value="url">URL</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="edit_key">Key</label>
                            <input name="key" id="edit_key" class="form-control key" type="text" autocomplete="off">
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="form-group mb-2">
                            <label for="remarks">Remarks</label>
                            <input id="edit_remarks" name="remarks" class="form-control remarks" type="text"
                                autocomplete="off">
                            @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="form-group mb-2">
                            <label for="edit_value">Value</label>
                            <!-- Input fields for different types -->
                            <input type="text" name="value" id="edit_value_text"
                                class="form-control value @error('value') is-invalid @enderror">
                            <input type="file" name="value_file" id="edit_value_file"
                                class="form-control value @error('value') is-invalid @enderror" style="display:none;">
                            <textarea name="value_textarea" id="edit_value_textarea"
                                class="form-control value @error('value') is-invalid @enderror"
                                style="display:none;"></textarea>
                            <input type="color" name="value_color" id="edit_value_color"
                                class="form-control form-control-color value @error('value') is-invalid @enderror"
                                style="display:none;">
                            <input type="number" name="value_number" id="edit_value_number"
                                class="form-control value @error('value') is-invalid @enderror" style="display:none;">
                            <input type="url" name="value_url" id="edit_value_url"
                                class="form-control value @error('value') is-invalid @enderror" style="display:none;">
                            <!-- New dropdown field -->
                            <select name="value_dropdown" id="edit_value_dropdown"
                                class="form-control value @error('value') is-invalid @enderror" style="display:none;">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-warning btn-sm"
                            data-coreui-dismiss="modal">@lang('backend.cancel')</button>
                        <button type="submit" class="btn btn-success btn-sm Update">@lang('backend.update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Edit modal Ends-->

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        $(document).ready(function () {
            let table = $('#{{ $name }}-datatable').DataTable();
            let baseRoute = "{{ url('/') }}/{{ $route }}";

            // Initialize add modal fields
            function initAddModalFields() {
                $('#value').show().attr('type', 'text');
                $('#value_textarea, #value_color, #value_number, #value_url, #value_dropdown, #value_file').hide();
            }

            // Initialize edit modal fields
            function initEditModalFields() {
                $('#edit_value_text').show().attr('type', 'text');
                $('#edit_value_textarea, #edit_value_color, #edit_value_number, #edit_value_url, #edit_value_dropdown, #edit_value_file').hide();
            }

            // Show add modal handler
            $('#add-modal-sm').on('show.bs.modal', function () {
                initAddModalFields();
                $('#type').val('');
            });

            // Show edit modal handler
            $('#edit-modal-sm').on('show.bs.modal', function () {
                initEditModalFields();
            });

            // Add form type change handler
            $('#type').change(function () {
                var selectedType = $(this).val();
                console.log(selectedType);
                // Hide all value fields
                $('#value, #value_textarea, #value_color, #value_number, #value_url, #value_dropdown, #value_file').hide();

                // Show the appropriate field based on type
                switch (selectedType) {
                    case 'textarea':
                        $('#value_textarea').show();
                        break;
                    case 'color':
                        $('#value_color').show();
                        break;
                    case 'number':
                        $('#value_number').show();
                        break;
                    case 'url':
                        $('#value_url').show();
                        break;
                    case 'dropdown':
                        $('#value_dropdown').show();
                        break;
                    case 'file':
                        $('#value_file').show();
                        break;
                    default:
                        $('#value').show().attr('type', 'text');
                }
            });

            // Edit form type change handler
            $('#edit_type').change(function () {
                var selectedType = $(this).val();

                // Hide all value fields
                $('#edit_value_text, #edit_value_textarea, #edit_value_color, #edit_value_number, #edit_value_url, #edit_value_file').hide();

                // Show the appropriate field based on type
                switch (selectedType) {
                    case 'textarea':
                        $('#edit_value_textarea').show();
                        break;
                    case 'color':
                        $('#edit_value_color').show();
                        break;
                    case 'number':
                        $('#edit_value_number').show();
                        break;
                    case 'url':
                        $('#edit_value_url').show();
                        break;
                    case 'file':
                        $('#edit_value_file').show();
                        break;
                    case 'dropdown':
                        $('#edit_value_dropdown').show();
                        break;
                    default:
                        $('#edit_value_text').show().attr('type', 'text');
                }
            });

            // Add form submission
            $('#add').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $(".Save").attr('disabled', true).html('Submitting...');

                // Get the actual value based on selected type
                let selectedType = $('#type').val();
                let valueField;
                switch (selectedType) {
                    case 'textarea': valueField = $('#value_textarea').val(); break;
                    case 'color': valueField = $('#value_color').val(); break;
                    case 'number': valueField = $('#value_number').val(); break;
                    case 'url': valueField = $('#value_url').val(); break;
                    case 'file': valueField = $('#value_file').val(); break;
                    case 'dropdown': valueField = $('#value_dropdown').val(); break;
                    default: valueField = $('#value').val();
                }
                formData.set('value', valueField);

                $.ajax({
                    type: "POST",
                    url: baseRoute,
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $(".Save").attr('disabled', false).html('Save');
                        if (response.type === 'success') {
                            showToast({
                                icon: response.type || 'success',
                                title: response.message || 'Operation successful'
                            });
                            $(`#addModal`).modal('hide');
                            $(`#add`).trigger("reset");
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (response) {
                        $(".Save").attr('disabled', false).html('Save');
                        var errors = response.responseJSON.errors;
                        $('.invalid-feedback').remove();
                        $("#add input:not([name='_token']), #add select, #add textarea").removeClass("is-invalid");
                        for (var field in errors) {
                            var errorMessage = errors[field][0];
                            $('[name="' + field + '"]').addClass("is-invalid").after(
                                '<div class="invalid-feedback">' + errorMessage + '</div>');
                        }
                    }
                });
            });

            // Edit form submission
            $('#updatE').on('submit', function (e) {
                e.preventDefault();
                $(".Update").attr('disabled', true).html('Updating...');
                let id = $(".id").val();
                let url = baseRoute + "/" + id;
                let formData = new FormData(this);
                formData.append('_method', 'PATCH');

                // Get the actual value based on selected type
                let selectedType = $('#edit_type').val();
                let valueField;
                switch (selectedType) {
                    case 'textarea': valueField = $('#edit_value_textarea').val(); break;
                    case 'color': valueField = $('#edit_value_color').val(); break;
                    case 'number': valueField = $('#edit_value_number').val(); break;
                    case 'url': valueField = $('#edit_value_url').val(); break;
                    case 'file': valueField = $('#edit_value_file').val(); break;
                    case 'dropdown': valueField = $('#edit_value_dropdown').val(); break;
                    default: valueField = $('#edit_value_text').val();
                }
                formData.set('value', valueField);

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $(".Update").attr('disabled', false).html('Update');
                        if (response.type === 'success') {
                            showToast({
                                icon: response.type || 'success',
                                title: response.message || 'Operation successful'
                            });
                            $(`#editModal`).modal('hide');
                            $(`#updatE`).trigger("reset");
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (response) {
                        $(".Update").attr('disabled', false).html('Update');
                        var errors = response.responseJSON.errors;
                        $('.invalid-feedback').remove();
                        $("#updatE input:not([name='_token']), #updatE select, #updatE textarea").removeClass("is-invalid");
                        for (var field in errors) {
                            var errorMessage = errors[field][0];
                            $('[name="' + field + '"]').addClass("is-invalid").after(
                                '<div class="invalid-feedback">' + errorMessage + '</div>');
                        }
                    }
                });
            });

            // Edit function
            window.edit = function (id) {
                $.ajax({
                    type: "GET",
                    url: `${baseRoute}/${id}/edit`,
                    success: function (data) {
                        $('.id').val(data.id);
                        $('#edit_type').val(data.type);
                        $('#edit_key').val(data.key);
                        $('#edit_remarks').val(data.remarks);

                        // Hide all value fields first
                        $('#edit_value_text, #edit_value_textarea, #edit_value_color, #edit_value_number, #edit_value_url, #edit_value_dropdown, #edit_value_file').hide();

                        // Set value based on type
                        switch (data.type) {
                            case 'textarea':
                                $('#edit_value_textarea').val(data.value).show();
                                break;
                            case 'color':
                                $('#edit_value_color').val(data.value).show();
                                break;
                            case 'number':
                                $('#edit_value_number').val(data.value).show();
                                break;
                            case 'url':
                                $('#edit_value_url').val(data.value).show();
                                break;
                            case 'dropdown':
                                $('#edit_value_dropdown').val(data.value).show();
                                break;
                            case 'file':
                                $('#edit_value_file').val('').show();
                                break;
                            default: // text
                                $('#edit_value_text').val(data.value).show().attr('type', 'text');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                    }
                });
            };
        });
    </script>
@endpush
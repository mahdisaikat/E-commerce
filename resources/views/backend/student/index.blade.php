@extends('backend.layout.master')

@push('styles')
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('backend.import_students')</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($route . '.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">@lang('backend.select_excel_file')</label>
                        <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv">
                        <small class="form-text text-muted">
                            @lang('backend.supported_formats')
                        </small>
                        @error('section')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-coreui-dismiss="modal">@lang('backend.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('backend.import')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! $dataTable->scripts() !!}

<script>

</script>
@endpush
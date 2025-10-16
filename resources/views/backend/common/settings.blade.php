@extends('backend.layout.master')

@push('styles')
@endpush

@section('content')
<div class="row">
    <div class="col-xl-12">
        <form method="POST" action="{{ route('configurations.settings') }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach ($data as $index => $setting)
                        <div class="col-md-6 mb-4">
                            <label for="setting_{{ $index }}" class="form-label text-capitalize fw-semibold">
                                {{ ucwords(str_replace('_', ' ', $setting['key'])) }}
                            </label>

                            @if ($setting['type'] === 'dropdown')
                            <select class="form-select" id="setting_{{ $index }}"
                                name="settings[{{ $setting['key'] }}]">
                                <option value="yes" {{ $setting['value']==='yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $setting['value']==='no' ? 'selected' : '' }}>No</option>
                            </select>

                            @elseif ($setting['type'] === 'color')
                            <input type="color" class="form-control form-control-color" id="setting_{{ $index }}"
                                name="settings[{{ $setting['key'] }}]" value="{{ $setting['value'] }}"
                                title="Choose color" />

                            @elseif ($setting['type'] === 'file')
                            @if (!empty($setting['value']))
                            <div class="mb-2">
                                <img src="{{ asset($setting['value']) }}" alt="Uploaded file"
                                    style="max-height: 100px; border: 1px solid #ccc; padding: 5px;">
                            </div>
                            @endif
                            <input type="file" class="form-control" id="setting_{{ $index }}"
                                name="file[{{ $setting['key'] }}]">

                            @else
                            <input type="{{ $setting['type'] }}" class="form-control" id="setting_{{ $index }}"
                                name="settings[{{ $setting['key'] }}]" value="{{ $setting['value'] }}" />
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
@endpush
@extends('backend.layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <form action="{{ route($route . '.update', ['role' => $role->id]) }}" method="post" class="needs-validation"
                novalidate>
                @csrf
                @method('patch')
                <div class="card-body">
                    <div class="row">
                        <label for="display_name" class="col-sm-3 col-form-label">Display Name</label>
                        <div class="col-sm-9">
                            <input name="display_name" class="form-control @error('display_name') is-invalid @enderror"
                                type="text" value="{{ old('display_name', $role->display_name) }}"
                                placeholder="Display Name" required autocomplete="display_name">
                            @error('display_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">Modules</th>
                                    <th width="10%">All</th>
                                    <th width="80%">Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $key => $permission)
                                @php
                                $spacelesskey = str_replace(' ', '', $key);
                                @endphp
                                <tr>
                                    <td><b>{{ $key }}</b></td>
                                    <td class="">
                                        @php
                                        $checkAllPb = '';
                                        if (!empty(old('selectall_' . $spacelesskey))) {
                                        $checkAllPb = 'checked';
                                        }
                                        @endphp
                                        <input type="checkbox" name="selectall_{{ $spacelesskey }}"
                                            id="selectall_{{ $spacelesskey }}" class="checkAll"
                                            module="{{ $spacelesskey }}" value="1" {{ $checkAllPb }} />
                                        <label for="selectall_{{ $spacelesskey }}">All</label>
                                    </td>
                                    <td>
                                        <div class="row">
                                            @foreach ($permission as $ikey => $value)
                                            @php
                                            $checked = '';
                                            if (
                                            (is_array(old('permission')) &&
                                            in_array($ikey, old('permission'))) ||
                                            in_array($ikey, $role_permissions)
                                            ) {
                                            $checked = 'checked';
                                            }
                                            @endphp
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                        class="form-check-input permcheck {{ $spacelesskey }}"
                                                        id="{{ $value }}" name="permission[]"
                                                        module="{{ $spacelesskey }}" value="{{ $ikey }}" {{ $checked
                                                        }} />
                                                    <label class="form-check-label" for="{{ $value }}">{{ $value
                                                        }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan=3 class="text-center">{{ __('No module available yet.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route($route . '.index') }}" type="button"
                        class="btn btn-outline-secondary btn-sm">Back</a>
                    <button type="submit" class="btn btn-outline-success btn-sm"
                        onClick="this.form.submit(); this.disabled=true; this.innerText='Updating .....';">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $('.permcheck').each(function() {
            var allChecked = true;
            var modulename = $(this).attr('module');
            $('.' + modulename).each(function() {
                if (!(this.checked)) {
                    allChecked = false;
                }
            });
            if (allChecked == true) {
                $("#selectall_" + modulename).prop("checked", true);
            } else {
                $("#selectall_" + modulename).prop("checked", false);
            }
        });

        $('.checkAll').click(function() {
            var modulename = $(this).attr('module');
            if ($(this).prop("checked") == true) {
                $("." + modulename).prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $("." + modulename).prop("checked", false);
            }
        });

        $('.permcheck').click(function() {
            var allChecked = true;
            var modulename = $(this).attr('module');
            $('.' + modulename).each(function() {
                if (!(this.checked)) {
                    allChecked = false;
                }
            });
            if (allChecked == true) {
                $("#selectall_" + modulename).prop("checked", true);
            } else {
                $("#selectall_" + modulename).prop("checked", false);
            }
        });
</script>
@endpush
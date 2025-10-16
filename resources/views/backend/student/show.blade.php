@extends('backend.layout.master')

@section('content')
<div class="card">
    <div class="card-header">@lang('backend.view_student'): {{ $student->name_en ?? $student->name_bn }}</div>
    <div class="card-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="studentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal"
                    type="button" role="tab">@lang('backend.basic') @lang('backend.info')</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="parents-tab" data-bs-toggle="tab" data-bs-target="#parents" type="button"
                    role="tab">@lang('backend.parents') @lang('backend.info')</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button"
                    role="tab">@lang('backend.academic') @lang('backend.info')</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other" type="button"
                    role="tab">@lang('backend.others') @lang('backend.info')</button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="studentTabsContent">

            <!-- Personal Info -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <div class="row">
                    <div class="col-md-6"><strong>@lang('backend.student_id'):</strong> {{ $student->student_id }}</div>
                    <div class="col-md-6"><strong>@lang('backend.name_bn'):</strong> {{ $student->name_bn }}</div>
                    <div class="col-md-6"><strong>@lang('backend.name_en'):</strong> {{ $student->name_en }}</div>
                    <div class="col-md-6"><strong>@lang('backend.dob'):</strong> {{ $student->dob?->format('d M Y') }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.birth_certificate_no'):</strong> {{
                        $student->birth_certificate_no }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.religion'):</strong> {{ $student->religion }}</div>
                    <div class="col-md-6"><strong>@lang('backend.gender'):</strong> {{ $student->gender }}</div>
                    <div class="col-md-6"><strong>@lang('backend.nationality'):</strong> {{ $student->nationality }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.profile_image'):</strong><br>
                        @if($student->profile_image)
                        <img src="{{ asset('storage/'.$student->profile_image) }}" width="120"
                            class="img-thumbnail mt-2">
                        {{-- @else
                        <span class="text-muted">@lang('backend.no_image')</span> --}}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Parents Info -->
            <div class="tab-pane fade" id="parents" role="tabpanel">
                <div class="row">
                    <div class="col-md-6"><strong>@lang('backend.father_name'):</strong> {{ $student->father_name }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.father_phone'):</strong> {{ $student->father_phone }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.father_occupation'):</strong> {{
                        $student->father_occupation }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.mother_name'):</strong> {{ $student->mother_name }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.mother_phone'):</strong> {{ $student->mother_phone }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.mother_occupation'):</strong> {{
                        $student->mother_occupation }}
                    </div>
                    <hr class="mt-2">
                    <div class="col-md-12"><strong>@lang('backend.present_address'):</strong> {{
                        $student->permanent_address }}
                    </div>
                    <div class="col-md-12"><strong>@lang('backend.permanent_address'):</strong> {{
                        $student->present_address }}</div>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="tab-pane fade" id="academic" role="tabpanel">
                <div class="row">
                    <div class="col-md-6"><strong>@lang('backend.class_applied'):</strong> {{ $student->class_applied }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.roll'):</strong> {{ $student->roll }}</div>
                    <div class="col-md-6"><strong>@lang('backend.shift'):</strong> {{ $student->shift }}</div>
                    <div class="col-md-6"><strong>@lang('backend.fee_waiver'):</strong> {{ $student->fee_waiver ? 'Yes'
                        : 'No' }}
                    </div>
                    <div class="col-md-6"><strong>@lang('backend.monthly_fee'):</strong> {{ $student->monthly_fee }}
                    </div>
                    <div class="col-md-6">
                        <strong>@lang('backend.section'):</strong>
                        {{ $student->section?->name }} {{ $student->section?->class ? '(' . $student->section?->class .
                        ')' : '' }}
                    </div>
                </div>
            </div>

            <!-- Other Info -->
            <div class="tab-pane fade" id="other" role="tabpanel">
                <div class="row">
                    <div class="col-md-6"><strong>@lang('backend.email'):</strong> {{ $student->email }}</div>
                    <div class="col-md-6"><strong>@lang('backend.phone'):</strong> {{ $student->phone }}</div>
                    <div class="col-md-6"><strong>@lang('backend.transport_type'):</strong> {{ $student->transport_type
                        }}</div>
                    <div class="col-md-6"><strong>@lang('backend.created_at'):</strong> {{
                        $student->created_at->format('d M Y, h:i
                        A') }}
                    </div>
                    <div class="col-md-6">
                        <strong>@lang('backend.status'):</strong>
                        {{ $student->status == 1 ? __('backend.active') : __('backend.inactive') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">@lang('backend.back')</a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">@lang('backend.edit')</a>
        </div>
    </div>
</div>
@endsection
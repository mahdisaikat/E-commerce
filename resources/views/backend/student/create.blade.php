@extends('backend.layout.master')

@section('content')
<div class="card">
    <div class="card-body">
        <form id="studentForm" method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Step indicators -->
            <ul class="nav nav-pills mb-3" id="pills-tab">
                <li class="nav-item"><button class="nav-link active" data-step="1">@lang('backend.step_1'):
                        @lang('backend.basic')</button></li>
                <li class="nav-item"><button class="nav-link" data-step="2">@lang('backend.step_2'): @lang(
                        'backend.parents')</button>
                </li>
                <li class="nav-item"><button class="nav-link" data-step="3">@lang('backend.step_3'): @lang(
                        'backend.address')</button>
                </li>
                <li class="nav-item"><button class="nav-link" data-step="4">@lang('backend.step_4'): @lang(
                        'backend.academic')</button>
                </li>
                <li class="nav-item"><button class="nav-link" data-step="5">@lang('backend.step_5'): @lang(
                        'backend.contact')</button>
                </li>
            </ul>

            <!-- Step 1 -->
            <div class="step step-1">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>@lang('backend.student_id')</label>
                        <input type="text" name="student_id" class="form-control" value="{{ $studentId }}" readonly>
                        @error('student_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.name_bn')</label>
                        <input type="text" name="name_bn" class="form-control" value="{{ old('name_bn') }}">
                        @error('name_bn')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.name_en')</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                        @error('name_en')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.dob')</label>
                        <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                        @error('dob')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.gender')</label>
                        <select name="gender" class="form-control">
                            <option value="">@lang('backend.select')</option>
                            <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>@lang('backend.male')
                            </option>
                            <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>
                                @lang('backend.female')</option>
                            <option value="Others" {{ old('gender')=='Others' ? 'selected' : '' }}>
                                @lang('backend.others')</option>
                        </select>
                        @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.religion')</label>
                        <input type="text" name="religion" class="form-control" value="{{ old('religion') }}">
                        @error('religion')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.nationality')</label>
                        <input type="text" name="nationality" class="form-control" value="{{ old('nationality') }}">
                        @error('nationality')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.birth_certificate_no')</label>
                        <input type="text" name="birth_certificate_no" class="form-control"
                            value="{{ old('birth_certificate_no') }}">
                        @error('birth_certificate_no')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step step-2 d-none">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>@lang('backend.father_name')</label>
                        <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}">
                        @error('father_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.father_phone')</label>
                        <input type="text" name="father_phone" class="form-control" value="{{ old('father_phone') }}">
                        @error('father_phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.father_occupation')</label>
                        <input type="text" name="father_occupation" class="form-control"
                            value="{{ old('father_occupation') }}">
                        @error('father_occupation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.mother_name')</label>
                        <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}">
                        @error('mother_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.mother_phone')</label>
                        <input type="text" name="mother_phone" class="form-control" value="{{ old('mother_phone') }}">
                        @error('mother_phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.mother_occupation')</label>
                        <input type="text" name="mother_occupation" class="form-control"
                            value="{{ old('mother_occupation') }}">
                        @error('mother_occupation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step step-3 d-none">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>@lang('backend.present_address')</label>
                        <textarea name="present_address" class="form-control">{{ old('present_address') }}</textarea>
                        @error('present_address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label>@lang('backend.permanent_address')</label>
                        <textarea name="permanent_address"
                            class="form-control">{{ old('permanent_address') }}</textarea>
                        @error('permanent_address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step step-4 d-none">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>@lang('backend.class_applied')</label>
                        <input type="text" name="class_applied" class="form-control" value="{{ old('class_applied') }}">
                        @error('class_applied')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.roll')</label>
                        <input type="number" name="roll" class="form-control" value="{{ old('roll') }}">
                        @error('roll')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.shift')</label>
                        <input type="text" name="shift" class="form-control" value="{{ old('shift') }}">
                        @error('shift')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.fee_waiver')</label>
                        <input type="number" name="fee_waiver" class="form-control" value="{{ old('fee_waiver') }}">
                        @error('fee_waiver')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.monthly_fee')</label>
                        <input type="number" name="monthly_fee" class="form-control" value="{{ old('monthly_fee') }}">
                        @error('monthly_fee')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.transport_type')</label>
                        <select name="transport_type" class="form-control">
                            <option value="Self" {{ old('transport_type')=='Self' ?'selected':'' }}>
                                @lang('backend.self')</option>
                            <option value="School Van" {{ old('transport_type')=='School Van' ?'selected':'' }}>
                                @lang('backend.school_van')</option>
                        </select>
                        @error('transport_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="step step-5 d-none">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>@lang('backend.email')</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.phone')</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label>@lang('backend.section')</label>
                        <select name="section_id" class="form-control">
                            <option value="">@lang('backend.select')</option>
                            @foreach ($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id')==$section->id ? 'selected' : '' }}>
                                {{ $section->name }} ({{ $section->class }})</option>
                            @endforeach
                        </select>
                        @error('section')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label>@lang('backend.profile_image')</label>
                        <input type="file" name="profile_image" class="form-control">
                        @error('profile_image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-4">
                <button type="button" class="btn btn-secondary prev-step d-none">@lang('backend.previous')</button>
                <button type="button" class="btn btn-primary next-step">@lang('backend.next')</button>
                <button type="submit" class="btn btn-success d-none submit-btn">@lang('backend.submit')</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 5;

    // Define required fields for each step
    const stepFields = {
        1: ['student_id', 'name_bn', 'name_en', 'dob', 'gender', 'nationality'], 
        2: ['father_name', 'mother_name'], 
        3: ['present_address', 'permanent_address'], 
        4: ['class_applied'], 
        5: ['status'] // profile_image optional
    };

    function showStep(step) {
        document.querySelectorAll('.step').forEach(el => el.classList.add('d-none'));
        document.querySelector('.step-' + step).classList.remove('d-none');

        // Update buttons
        document.querySelector('.prev-step').classList.toggle('d-none', step === 1);
        document.querySelector('.next-step').classList.toggle('d-none', step === totalSteps);
        document.querySelector('.submit-btn').classList.toggle('d-none', step !== totalSteps);

        // Update nav pills
        document.querySelectorAll('#pills-tab .nav-link').forEach((btn, i) => {
            btn.classList.toggle('active', (i + 1) === step);
        });
    }

    function validateStep(step) {
        let valid = true;
        stepFields[step].forEach(name => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) {
                // Remove previous error
                field.classList.remove('is-invalid');
                let value = field.value.trim();
                if (!value) {
                    valid = false;
                    field.classList.add('is-invalid');
                }
            }
        });
        return valid;
    }

    document.querySelector('.next-step').addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        } else {
            showToast({
                icon: 'error',
                title: 'Please fill all required fields in this step.'
            });
        }
    });

    document.querySelector('.prev-step').addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Nav pills click
    document.querySelectorAll('#pills-tab .nav-link').forEach((btn, i) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            // Validate current step before moving
            if (i + 1 < currentStep) { // allow going back without validation
                currentStep = i + 1;
                showStep(currentStep);
            } else {
                if (validateStep(currentStep)) {
                    currentStep = i + 1;
                    showStep(currentStep);
                } else {
                    showToast({
                        icon: 'error',
                        title: 'Please fill all required fields in this step.'
                    });
                }
            }
        });
    });

    // Initialize
    showStep(currentStep);

</script>
@endpush
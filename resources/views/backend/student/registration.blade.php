<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #5f75ee;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
            --body-bg: #f5f7fb;
            --card-bg: #ffffff;
            --text-color: #212529;
            --border-color: #e1e5eb;
            --shadow-color: rgba(0, 0, 0, 0.08);
            --step-bg: #ffffff;
            --step-border: #e9ecef;
            --preview-bg: #f8f9fa;
        }

        [data-theme="dark"] {
            --primary: #5f75ee;
            --primary-dark: #4361ee;
            --secondary: #9fa6b2;
            --body-bg: #121212;
            --card-bg: #1e1e1e;
            --text-color: #e6e6e6;
            --border-color: #444;
            --shadow-color: rgba(0, 0, 0, 0.5);
            --step-bg: #2d2d2d;
            --step-border: #444;
            --preview-bg: #2d2d2d;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            transition: background-color 0.3s, color 0.3s;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 6px 15px var(--shadow-color);
            border: none;
            margin-top: 20px;
            background-color: var(--card-bg);
            transition: background-color 0.3s;
        }

        .card-header {
            background: linear-gradient(120deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 25px;
            font-weight: 600;
            font-size: 1.4rem;
            border: none;
        }

        .card-body {
            padding: 30px;
            background-color: var(--card-bg);
            transition: background-color 0.3s;
        }

        .step-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 4px;
            background-color: var(--step-border);
            z-index: 1;
            transition: background-color 0.3s;
        }

        .progress-bar {
            position: absolute;
            top: 20px;
            left: 0;
            height: 4px;
            background: var(--primary);
            z-index: 2;
            transition: width 0.5s ease, background-color 0.3s;
        }

        .step-item {
            text-align: center;
            position: relative;
            z-index: 3;
        }

        .step-icon {
            width: 44px;
            height: 44px;
            background-color: var(--step-bg);
            border: 4px solid var(--step-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: var(--secondary);
            transition: all 0.3s ease;
        }

        .step-item.active .step-icon {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .step-item.completed .step-icon {
            background-color: var(--success);
            border-color: var(--success);
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--secondary);
            transition: color 0.3s;
        }

        .step-item.active .step-label,
        .step-item.completed .step-label {
            color: var(--text-color);
        }

        .step-content {
            padding: 20px 0;
        }

        .step-title {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--primary);
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--step-border);
            transition: border-color 0.3s;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-color);
            transition: color 0.3s;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 2px solid var(--border-color);
            transition: all 0.3s;
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--primary), var(--primary-dark));
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(120deg, var(--primary-dark), var(--primary));
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--secondary);
            border: none;
        }

        .btn-success {
            background: linear-gradient(120deg, var(--success), #0f5132);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(120deg, #0f5132, var(--success));
            transform: translateY(-2px);
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid var(--step-border);
            transition: border-color 0.3s;
        }

        .required-field::after {
            content: '*';
            color: var(--danger);
            margin-left: 3px;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }

        .form-control.error,
        .form-select.error {
            border-color: var(--danger);
        }

        .step-preview {
            background-color: var(--preview-bg);
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .step-preview h6 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        .preview-item {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: var(--text-color);
            transition: color 0.3s;
        }

        .preview-item strong {
            color: var(--text-color);
            margin-right: 5px;
            transition: color 0.3s;
        }

        .theme-switcher {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(120deg, var(--primary), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .theme-switcher:hover {
            transform: scale(1.1);
        }

        .form-text {
            color: var(--secondary) !important;
        }
    </style>
</head>

<body>
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-graduate me-2"></i> Student Registration
                    </div>
                    <div class="card-body">
                        <!-- Step Progress -->
                        <div class="step-progress">
                            <div class="progress-bar" style="width: 0%;"></div>
                            <div class="step-item active" data-step="1">
                                <div class="step-icon">1</div>
                                <div class="step-label">Basic Info</div>
                            </div>
                            <div class="step-item" data-step="2">
                                <div class="step-icon">2</div>
                                <div class="step-label">Parents</div>
                            </div>
                            <div class="step-item" data-step="3">
                                <div class="step-icon">3</div>
                                <div class="step-label">Address</div>
                            </div>
                            <div class="step-item" data-step="4">
                                <div class="step-icon">4</div>
                                <div class="step-label">Academic</div>
                            </div>
                            <div class="step-item" data-step="5">
                                <div class="step-icon">5</div>
                                <div class="step-label">Contact</div>
                            </div>
                        </div>

                        <form id="studentForm" method="POST" action="{{ route('students.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Step 1 -->
                            <div class="step-content step-1">
                                <h3 class="step-title"><i class="fas fa-user me-2"></i> Basic Information</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Student ID</label>
                                        <input type="text" name="student_id" class="form-control"
                                            value="{{ $studentId }}" readonly>
                                        <div class="error-message">Please enter a valid student ID</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Name (English)</label>
                                        <input type="text" name="name_en" class="form-control" required>
                                        <div class="error-message">Please enter the student's name in English</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name (Bengali)</label>
                                        <input type="text" name="name_bn" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Date of Birth</label>
                                        <input type="date" name="dob" class="form-control" required>
                                        <div class="error-message">Please select a valid date of birth</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Gender</label>
                                        <select name="gender" class="form-select" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="error-message">Please select a gender</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Religion</label>
                                        <input type="text" name="religion" class="form-control" required>
                                        <div class="error-message">Please enter a religion</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Nationality</label>
                                        <input type="text" name="nationality" class="form-control" required>
                                        <div class="error-message">Please enter a nationality</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Birth Certificate No</label>
                                        <input type="text" name="birth_certificate_no" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="step-content step-2 d-none">
                                <h3 class="step-title"><i class="fas fa-users me-2"></i> Parents Information</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Father's Name</label>
                                        <input type="text" name="father_name" class="form-control" required>
                                        <div class="error-message">Please enter father's name</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Mother's Name</label>
                                        <input type="text" name="mother_name" class="form-control" required>
                                        <div class="error-message">Please enter mother's name</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Father's Phone</label>
                                        <input type="tel" name="father_phone" class="form-control" required>
                                        <div class="error-message">Please enter a valid phone number</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Mother's Phone</label>
                                        <input type="tel" name="mother_phone" class="form-control" required>
                                        <div class="error-message">Please enter a valid phone number</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Father's Occupation</label>
                                        <input type="text" name="father_occupation" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mother's Occupation</label>
                                        <input type="text" name="mother_occupation" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="step-content step-3 d-none">
                                <h3 class="step-title"><i class="fas fa-home me-2"></i> Address Information</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Present Address</label>
                                        <textarea name="present_address" class="form-control" rows="4"
                                            required></textarea>
                                        <div class="error-message">Please enter present address</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control" rows="4"
                                            required></textarea>
                                        <div class="error-message">Please enter permanent address</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sameAsPresent">
                                            <label class="form-check-label" for="sameAsPresent">
                                                Same as present address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="step-content step-4 d-none">
                                <h3 class="step-title"><i class="fas fa-graduation-cap me-2"></i> Academic Information
                                </h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Class Applied</label>
                                        <select name="class_applied" class="form-select" required>
                                            <option value="">Select Class</option>
                                            <option value="Class 1">Class 1</option>
                                            <option value="Class 2">Class 2</option>
                                            <option value="Class 3">Class 3</option>
                                            <option value="Class 4">Class 4</option>
                                            <option value="Class 5">Class 5</option>
                                            <option value="Class 6">Class 6</option>
                                            <option value="Class 7">Class 7</option>
                                            <option value="Class 8">Class 8</option>
                                            <option value="Class 9">Class 9</option>
                                            <option value="Class 10">Class 10</option>
                                        </select>
                                        <div class="error-message">Please select a class</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Roll Number</label>
                                        <input type="text" name="roll" class="form-control" required>
                                        <div class="error-message">Please enter a roll number</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Shift</label>
                                        <select name="shift" class="form-select" required>
                                            <option value="">Select Shift</option>
                                            <option value="Morning">Morning</option>
                                            <option value="Day">Day</option>
                                            <option value="Evening">Evening</option>
                                        </select>
                                        <div class="error-message">Please select a shift</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fee Waiver (%)</label>
                                        <input type="number" name="fee_waiver" class="form-control" min="0" max="100">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Monthly Fee (৳)</label>
                                        <input type="number" name="monthly_fee" class="form-control" required>
                                        <div class="error-message">Please enter monthly fee amount</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transport Type</label>
                                        <select name="transport_type" class="form-select">
                                            <option value="">Select Transport</option>
                                            <option value="Bus">Bus</option>
                                            <option value="Van">Van</option>
                                            <option value="None">None</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="step-content step-5 d-none">
                                <h3 class="step-title"><i class="fas fa-address-card me-2"></i> Contact Information</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Email Address</label>
                                        <input type="email" name="email" class="form-control" required>
                                        <div class="error-message">Please enter a valid email address</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" required>
                                        <div class="error-message">Please enter a valid phone number</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required-field">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <div class="error-message">Please select a status</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Profile Image</label>
                                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                                        <small class="form-text text-muted">Max file size: 2MB (JPG, PNG)</small>
                                    </div>
                                </div>

                                <!-- Preview Section -->
                                <div class="step-preview mt-4">
                                    <h6><i class="fas fa-eye me-2"></i> Registration Summary</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="preview-item"><strong>Student ID:</strong> <span
                                                    id="preview-student-id"></span></div>
                                            <div class="preview-item"><strong>Name:</strong> <span
                                                    id="preview-name"></span></div>
                                            <div class="preview-item"><strong>Class:</strong> <span
                                                    id="preview-class"></span></div>
                                            <div class="preview-item"><strong>Roll:</strong> <span
                                                    id="preview-roll"></span></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="preview-item"><strong>Father's Name:</strong> <span
                                                    id="preview-father"></span></div>
                                            <div class="preview-item"><strong>Mother's Name:</strong> <span
                                                    id="preview-mother"></span></div>
                                            <div class="preview-item"><strong>Phone:</strong> <span
                                                    id="preview-phone"></span></div>
                                            <div class="preview-item"><strong>Status:</strong> <span
                                                    id="preview-status"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="navigation-buttons">
                                <button type="button" class="btn btn-secondary prev-step d-none">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary next-step">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button type="submit" class="btn btn-success d-none submit-btn">
                                    <i class="fas fa-check me-2"></i> Submit Registration
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="theme-switcher" id="themeSwitcher">
        <i class="fas fa-moon"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 5;
            const form = document.getElementById('studentForm');
            const themeSwitcher = document.getElementById('themeSwitcher');
            
            // Theme switching functionality
            function initTheme() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                setTheme(savedTheme);
            }
            
            function setTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                
                if (theme === 'dark') {
                    themeSwitcher.innerHTML = '<i class="fas fa-sun"></i>';
                } else {
                    themeSwitcher.innerHTML = '<i class="fas fa-moon"></i>';
                }
            }
            
            function toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                setTheme(newTheme);
            }
            
            themeSwitcher.addEventListener('click', toggleTheme);
            initTheme();
            
            // Update progress bar
            function updateProgressBar() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                document.querySelector('.progress-bar').style.width = progressPercentage + '%';
                
                // Update step items
                document.querySelectorAll('.step-item').forEach((item, index) => {
                    if (index + 1 < currentStep) {
                        item.classList.add('completed');
                        item.classList.remove('active');
                    } else if (index + 1 === currentStep) {
                        item.classList.add('active');
                        item.classList.remove('completed');
                    } else {
                        item.classList.remove('active', 'completed');
                    }
                });
            }
            
            // Show current step
            function showStep(step) {
                document.querySelectorAll('.step-content').forEach(el => {
                    el.classList.add('d-none');
                });
                document.querySelector('.step-' + step).classList.remove('d-none');
                
                // Update buttons
                document.querySelector('.prev-step').classList.toggle('d-none', step === 1);
                document.querySelector('.next-step').classList.toggle('d-none', step === totalSteps);
                document.querySelector('.submit-btn').classList.toggle('d-none', step !== totalSteps);
                
                updateProgressBar();
                
                // Update preview on last step
                if (step === totalSteps) {
                    updatePreview();
                }
            }
            
            // Validate current step
            function validateStep(step) {
                let isValid = true;
                const inputs = document.querySelectorAll('.step-' + step + ' [required]');
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        showError(input, 'This field is required');
                        isValid = false;
                    } else {
                        clearError(input);
                        
                        // Additional validation for specific fields
                        if (input.type === 'email' && !isValidEmail(input.value)) {
                            showError(input, 'Please enter a valid email address');
                            isValid = false;
                        }
                        
                        if (input.type === 'tel' && !isValidPhone(input.value)) {
                            showError(input, 'Please enter a valid phone number');
                            isValid = false;
                        }
                    }
                });
                
                return isValid;
            }
            
            // Email validation
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
            
            // Phone validation
            function isValidPhone(phone) {
                const re = /^[0-9]{10,15}$/;
                return re.test(phone.replace(/\D/g, ''));
            }
            
            // Show error
            function showError(input, message) {
                input.classList.add('error');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                }
            }
            
            // Clear error
            function clearError(input) {
                input.classList.remove('error');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.style.display = 'none';
                }
            }
            
            // Update preview
            function updatePreview() {
                document.getElementById('preview-student-id').textContent = form.student_id.value || 'Not provided';
                document.getElementById('preview-name').textContent = form.name_en.value || 'Not provided';
                document.getElementById('preview-class').textContent = form.class_applied.value || 'Not provided';
                document.getElementById('preview-roll').textContent = form.roll.value || 'Not provided';
                document.getElementById('preview-father').textContent = form.father_name.value || 'Not provided';
                document.getElementById('preview-mother').textContent = form.mother_name.value || 'Not provided';
                document.getElementById('preview-phone').textContent = form.phone.value || 'Not provided';
                document.getElementById('preview-status').textContent = form.status.value === '1' ? 'Active' : 'Inactive';
            }
            
            // Next step button
            document.querySelector('.next-step').addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
            
            // Previous step button
            document.querySelector('.prev-step').addEventListener('click', function() {
                currentStep--;
                showStep(currentStep);
            });
            
            // Step progress items click
            document.querySelectorAll('.step-item').forEach(item => {
                item.addEventListener('click', function() {
                    const step = parseInt(this.getAttribute('data-step'));
                    if (step < currentStep) {
                        currentStep = step;
                        showStep(currentStep);
                    }
                });
            });
            
            // Same as present address checkbox
            document.getElementById('sameAsPresent').addEventListener('change', function() {
                if (this.checked) {
                    form.permanent_address.value = form.present_address.value;
                    form.permanent_address.setAttribute('readonly', true);
                } else {
                    form.permanent_address.removeAttribute('readonly');
                }
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateStep(currentStep)) {
                    // Show loading state
                    const submitBtn = document.querySelector('.submit-btn');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
                    submitBtn.disabled = true;
                    
                    // Create FormData object to handle file uploads
                    const formData = new FormData(form);
                    
                    // Simulate form submission (replace with actual fetch/AJAX call)
                    setTimeout(() => {
                        // In a real application, you would use fetch or XMLHttpRequest
                        // Example:
                        /*
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                successToast.show();
                                form.reset();
                                // Redirect or show success message
                            } else {
                                errorToast.show();
                            }
                        })
                        .catch(error => {
                            errorToast.show();
                        })
                        .finally(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        });
                        */
                        
                        // For demo purposes, we'll just show a success message
                        successToast.show();
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        
                        // Reset form and go back to first step after successful submission
                        setTimeout(() => {
                            form.reset();
                            currentStep = 1;
                            showStep(currentStep);
                        }, 2000);
                    }, 1500);
                }
                
                // If all validations pass, the form will submit normally
            });
            
            // Initialize the form
            showStep(currentStep);
            
            // Add input validation on blur for required fields
            document.querySelectorAll('[required]').forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showError(this, 'This field is required');
                    } else {
                        clearError(this);
                    }
                });
            });
        });
    </script>
</body>

</html>
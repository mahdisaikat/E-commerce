<?php

use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function ()
{
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
});

Route::get('login', function ()
{
    return view('frontend.login');
});

Route::get('register', function ()
{
    return view('backend.auth.register');
});

require __DIR__ . '/backend.php';

Route::fallback(function ()
{
    return view('backend.common.errors');
})->where('any', '.*');

Route::middleware(['auth'])->group(function ()
{
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::post('users-status', [UserController::class, 'status'])->name('users.status');
    Route::prefix('users/')->name('users.permission.')->group(function ()
    {
        Route::get('permission/{id}', [UserController::class, 'edit_permission'])->name('edit');
        Route::post('permission/{id}', [UserController::class, 'update_permission'])->name('update');
    });

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [ProfileController::class, 'edit'])->name('profile.update');

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::resource('sidebars', SidebarController::class);
    Route::post('sidebars-status', [SidebarController::class, 'status'])->name('sidebars.status');

    // .............. Configuration ...............
    Route::resource('configurations', ConfigurationController::class);
    Route::post('configurations-status', [ConfigurationController::class, 'status'])->name('configurations.status');
    Route::get('color-config', [ConfigurationController::class, 'config_settings'])->name('color.config');
    Route::post('configurations-settings', [ConfigurationController::class, 'system_settings_update'])->name('configurations.settings');
    Route::get('system-settings', [ConfigurationController::class, 'system_settings'])->name('system.settings');

    // .............. Designation ...............
    Route::resource('designations', DesignationController::class);
    Route::post('designations-status', [DesignationController::class, 'status'])->name('designations.status');

    // .............. Teacher ...............
    Route::resource('teachers', TeacherController::class);
    Route::post('teachers-status', [TeacherController::class, 'status'])->name('teachers.status');

    // .............. Section ...............
    Route::resource('sections', SectionController::class);
    Route::post('sections-status', [SectionController::class, 'status'])->name('sections.status');

    // .............. Exam ...............
    Route::resource('exams', ExamController::class);
    Route::post('exams-status', [ExamController::class, 'status'])->name('exams.status');

    // .............. Subject ...............
    Route::resource('subjects', SubjectController::class);
    Route::post('subjects-status', [SubjectController::class, 'status'])->name('subjects.status');

    // .............. Result ...............
    Route::resource('results', ResultController::class);
    Route::post('results-status', [ResultController::class, 'status'])->name('results.status');

    // .............. Schedule ...............
    Route::resource('schedules', ScheduleController::class);
    Route::post('schedules-status', [ScheduleController::class, 'status'])->name('schedules.status');

    // .............. Student ...............
    Route::resource('students', StudentController::class);
    Route::post('students-status', [StudentController::class, 'status'])->name('students.status');
    Route::get('students-registration', [StudentController::class, 'registration'])->name('students.registration');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students-download-sample', [StudentController::class, 'downloadSample'])->name('students.download-sample');



});


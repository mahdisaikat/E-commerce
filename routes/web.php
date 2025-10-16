<?php

use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\NewsletterController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductTagController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\TagController;
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


Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
});

Route::get('login', function () {
    return view('frontend.login');
});

Route::get('register', function () {
    return view('backend.auth.register');
});

require __DIR__ . '/backend.php';

Route::fallback(function () {
    return view('backend.common.errors');
})->where('any', '.*');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::post('users-status', [UserController::class, 'status'])->name('users.status');
    Route::prefix('users/')->name('users.permission.')->group(function () {
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

    // .............. Category ...............
    Route::resource('categories', CategoryController::class);
    Route::post('categories-status', [CategoryController::class, 'status'])->name('categories.status');
    Route::get('categories-get', [CategoryController::class, 'getCategory'])->name('categories.get');
    Route::get('categories-products', [CategoryController::class, 'getCategoryProducts'])->name('categories.products');

    // .............. Post ...............
    Route::resource('posts', PostController::class);
    Route::post('posts-status', [PostController::class, 'status'])->name('posts.status');

    // .............. Product ...............
    Route::resource('products', ProductController::class);
    Route::post('products-status', [ProductController::class, 'status'])->name('products.status');
    Route::get('products/get', [ProductController::class, 'getProduct'])->name('products.get');

    // .............. Contact ...............
    Route::resource('contacts', ContactController::class);
    Route::post('contacts-status', [ContactController::class, 'status'])->name('contacts.status');

    // .............. Newsletter ...............
    Route::resource('newsletters', NewsletterController::class);
    Route::post('newsletters-status', [NewsletterController::class, 'status'])->name('newsletters.status');

    // .............. Slider ...............
    Route::resource('sliders', SliderController::class);
    Route::post('sliders-status', [SliderController::class, 'status'])->name('sliders.status');

    // .............. Tag ...............
    Route::resource('tags', TagController::class);
    Route::post('tags-status', [TagController::class, 'status'])->name('tags.status');

    // .............. Product Tag ...............
    Route::resource('product-tags', ProductTagController::class);
    Route::post('product-tags-status', [ProductTagController::class, 'status'])->name('product-tags.status');

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


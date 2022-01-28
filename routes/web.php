<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WarningController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PlantUnitController;
use App\Http\Controllers\UnitModelController;
use App\Http\Controllers\UnitPremiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LoadCategoryController;
use App\Http\Controllers\ProdParameterController;
use App\Http\Controllers\WarningCategoryController;
use App\Http\Controllers\AttendanceCategoryController;

Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout']);

Route::get('register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('register', [RegisterController::class, 'store']);

Route::middleware('auth')->group(function(){
    Route::get('/', function () {
        return view('home', ['title' => 'Dashboard']);
    });

    Route::get('home', function () {
        return view('home', ['title' => 'Dashboard']);
    });

    Route::get('projects/import', [ProjectController::class, 'import']);
    Route::post('projects/importProcess', [ProjectController::class, 'importProcess']);
    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/add', [ProjectController::class, 'add']);
    Route::post('projects', [ProjectController::class, 'addProcess']);
    Route::get('projects/edit/{id}', [ProjectController::class, 'edit']);
    Route::patch('projects/{id}', [ProjectController::class, 'editProcess']);
    Route::delete('projects/{id}', [ProjectController::class, 'delete']);

    Route::resource('load_categories', LoadCategoryController::class);

    Route::get('unit_models/data', [UnitModelController::class, 'index_data'])->name('unit_models.index.data');
    Route::get('unit_models/import', [UnitModelController::class, 'import']);
    Route::get('unit_models/export', [UnitModelController::class, 'export'])->name('export');
    Route::post('unit_models/importProcess', [UnitModelController::class, 'importProcess']);
    Route::resource('unit_models', UnitModelController::class);
    
    Route::get('units/data', [UnitController::class, 'index_data'])->name('units.index.data');
    Route::get('units/import', [UnitController::class, 'import']);
    Route::get('units/export', [UnitController::class, 'export'])->name('export');
    Route::post('units/importProcess', [UnitController::class, 'importProcess']);
    Route::resource('units', UnitController::class);

    Route::get('unit_premis/data', [UnitPremiController::class, 'index_data'])->name('unit_premis.index.data');
    Route::resource('unit_premis', UnitPremiController::class);

    Route::get('plant_units/data', [PlantUnitController::class, 'index_data'])->name('plant_units.index.data');
    Route::resource('plant_units', PlantUnitController::class);

    Route::prefix('employees')->group(function(){
        Route::get('/data', [EmployeeController::class, 'index_data'])->name('employees.index.data');
        Route::get('/trash', [EmployeeController::class, 'trash']);
        Route::get('/restore/{id?}', [EmployeeController::class, 'restore']);
        Route::get('/delete/{id?}', [EmployeeController::class, 'delete']);
        Route::resource('/', EmployeeController::class)->parameters(['' => 'employee']);
    });

    Route::prefix('attendance_categories')->group(function(){
        Route::get('/', [AttendanceCategoryController::class, 'index'])->name('attendance_category.index');
        Route::get('/add', [AttendanceCategoryController::class, 'add']);
        Route::post('/', [AttendanceCategoryController::class, 'addProcess']);
        Route::get('/edit/{id}', [AttendanceCategoryController::class, 'edit']);
        Route::patch('/{id}', [AttendanceCategoryController::class, 'editProcess']);
        Route::delete('/{id}', [AttendanceCategoryController::class, 'delete']);
    });

    Route::get('warning_categories', [WarningCategoryController::class, 'index']);
    Route::get('warning_categories/add', [WarningCategoryController::class, 'add']);
    Route::post('warning_categories', [WarningCategoryController::class, 'addProcess']);
    Route::get('warning_categories/edit/{id}', [WarningCategoryController::class, 'edit']);
    Route::patch('warning_categories/{id}', [WarningCategoryController::class, 'editProcess']);
    Route::delete('warning_categories/{id}', [WarningCategoryController::class, 'delete']);

    Route::get('warnings/data', [WarningController::class, 'index_data'])->name('warnings.index.data');
    Route::get('warnings/trash', [WarningController::class, 'trash']);
    Route::get('warnings/restore/{id?}', [WarningController::class, 'restore']);
    Route::get('warnings/delete/{id?}', [WarningController::class, 'delete']);
    Route::resource('warnings', WarningController::class);

    Route::prefix('attendances')->group(function(){
        Route::get('/index.data', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/data', [AttendanceController::class, 'index_data'])->name('attendances.index.data');
        Route::get('/trash', [AttendanceController::class, 'trash']);
        Route::get('/restore/{id?}', [AttendanceController::class, 'restore']);
        Route::get('/delete/{id?}', [AttendanceController::class, 'delete']);
        Route::resource('/', AttendanceController::class)->parameters(['' => 'attendance']);
    });

    Route::resource('prod_parameters', ProdParameterController::class);
    
    Route::resource('users', UserController::class)->middleware('role:superadmin');
});
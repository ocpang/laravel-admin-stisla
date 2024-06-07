<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::get('/', function () {
    // return view('welcome');
    return redirect(route('admin.dashboard'));
});

Route::get('clear-caches', function () {
    // Artisan::call('cache:clear');
    // Artisan::call('config:clear');
    // Artisan::call('view:clear');
    // Artisan::call('route:clear');
    // Artisan::call('view:cache');
    // Artisan::call('config:cache');
    // Artisan::call('route:cache');
    Artisan::call('optimize:clear');
    // Artisan::call('migrate');
    return 'All Cache Cleared';
})->name('clear-caches');

Route::get('error-logs', [LogViewerController::class, 'index']);

Route::group(['prefix' => 'back', 'middleware' => ['auth']], function(){
    Route::group(['as' => 'admin.'], function(){
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/profile', ProfileController::class)->name('profile');

        // ADMIN
        Route::resource('user', UserController::class);
        Route::resource('role', RoleController::class);
        Route::get('role/assign/{id}', [RoleController::class, 'assign'])->name('role.assign');
        Route::post('role/assign-save/{id}', [RoleController::class, 'assignSave'])->name('role.assign-save');
        Route::resource('permission', PermissionController::class);
    });

});

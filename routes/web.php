<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Bookings\CheckIn\CheckInController;
use App\Http\Controllers\Bookings\CheckOut\CheckOutController;


// Route::redirect('/', 'login');

Route::middleware(['guest'])->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('login');
    Route::post('/', [SessionController::class, 'login']);
});
Route::get('/home', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/account/getData', [UserController::class, 'accountGetData'])->name('account.getData');
    Route::get('/users/getList', [UserController::class, 'userGetData'])->name('users.getList');
    Route::get('/users/getMainMenus', [UserController::class, 'usergetMainMenus'])->name('menus.getMainMenus');

    Route::get('/menus/getData', [UserController::class, 'getData'])->name('menus.getData');
    Route::get('/menus/getUserAccess', [UserController::class, 'getUserAccess'])->name('menus.getUserAccess');

    Route::get('/settings/users-management', [UserController::class, 'index'])->name('users-management');

    Route::get('/settings/users-management/new', [UserController::class, 'indexNew'])->name('users-newManagement');
    Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');
    Route::post('/settings/users-management/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/settings/users-management/edit', [UserController::class, 'indexEdit'])->name('users-editManagement');
    Route::put('/settings/users/{user}', [UserController::class, 'updateUsers'])->name('users.update');

    Route::get('/settings/users-management/delete', [UserController::class, 'indexDelete'])->name('users-deleteManagement');
    Route::post('/settings/users/{user}/deactivate', [UserController::class, 'deactivateUser'])->name('users.deactivate');


    Route::get('/settings/users-access-management', [UserController::class, 'indexUserAccessManagement'])->name('users-access-management');
    Route::get('/user-access/edit', [UserController::class, 'indexUserAccessManagement'])->name('user-access.edit');
    Route::get('/user-access/{userId}/permissions', [UserController::class, 'getUserPermissions']);
    Route::post('/user-access/{userId}/update', [UserController::class, 'update']);

    Route::prefix('bookings')->group(function () {
        Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
        Route::post('/check-in/{id}', [CheckInController::class, 'checkIn'])->name('bookings.checkin');

        Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout.index');        
    });

    Route::prefix('warehouse')->group(function () {
     
    });

    Route::prefix('archive')->group(function () {

      
    });

    Route::prefix('master')->group(function () {
     
    });
});

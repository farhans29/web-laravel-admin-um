<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Bookings\CheckIn\CheckInController;
use App\Http\Controllers\Bookings\CheckOut\CheckOutController;
use App\Http\Controllers\Rooms\ChangeRoomController;
use App\Http\Controllers\Properties\ManajementPropertiesController;
use App\Http\Controllers\Properties\ManajementRoomsController;
use App\Http\Controllers\Payment\PaymentController;

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
        Route::post('/checkin/{order_id}', [CheckInController::class, 'checkIn'])->name('bookings.checkin');
        Route::get('/check-in/{order_id}/details', [CheckInController::class, 'getBookingDetails'])->name('bookings.checkin.details');

        Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout.index');
        Route::post('/check-out/{id}', [CheckOutController::class, 'checkOut'])->name('bookings.checkout');
    });

    Route::prefix('rooms')->group(function () {
        Route::get('/change-room', [ChangeRoomController::class, 'index'])->name('changerooom.index');
        Route::get('/change-room/available-rooms', [ChangeRoomController::class, 'getAvailableRooms']);
        Route::post('/change-room/store', [ChangeRoomController::class, 'store'])->name('changeroom.store');
    });

    Route::prefix('properties')->group(function () {
        Route::get('/m-properties', [ManajementPropertiesController::class, 'index'])->name('properties.index');
        Route::put('/m-properties/{property}/status', [ManajementPropertiesController::class, 'updateStatus'])->name('properties.updateStatus');
        Route::post('/m-properties/store', [ManajementPropertiesController::class, 'store'])->name('properties.store');
        Route::put('/m-properties/update/{idrec}', [ManajementPropertiesController::class, 'update'])->name('properties.update');
        
        // ------------------------- ROOMS MANAGEMENT -------------------------
        Route::get('/rooms', [ManajementRoomsController::class, 'index'])->name('rooms.index');
        Route::post('/rooms/store', [ManajementRoomsController::class, 'store'])->name('rooms.store');
        Route::post('/rooms/update/{idrec}', [ManajementRoomsController::class, 'update'])->name('rooms.update');

        Route::get('/rooms/{room}/edit-prices', [ManajementRoomsController::class, 'changePriceIndex'])->name('rooms.prices.change-price-index');
        // Route::put('/rooms/{room}/edit-prices', [ManajementRoomsController::class, 'updatePrice'])->name('rooms.prices.update');
        Route::get('/rooms/{room}/price', [ManajementRoomsController::class, 'getPriceForDate'])->name('rooms.prices.date');
        Route::post('/rooms/{room}/update-price', [ManajementRoomsController::class, 'updatePriceRange'])->name('rooms.prices.update');

        Route::get('/rooms/{room}/prices', [ManajementRoomsController::class, 'getRoomPrices'])->name('rooms.prices.index');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/pay', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/payments/filter', [PaymentController::class, 'filter'])->name('admin.payments.filter');
        Route::post('/approve/{id}', [PaymentController::class, 'approve'])->name('admin.payments.approve');
        Route::post('/reject/{id}', [PaymentController::class, 'reject'])->name('admin.payments.reject');
    });

    Route::prefix('master')->group(function () {});
});

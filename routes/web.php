<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Bookings\Booking\AllBookingController;
use App\Http\Controllers\Bookings\CheckIn\CheckInController;
use App\Http\Controllers\Bookings\CheckOut\CheckOutController;
use App\Http\Controllers\Bookings\Completed\CompletedController;
use App\Http\Controllers\Bookings\NewReservation\NewReservController;
use App\Http\Controllers\Bookings\Pending\PendingController;
use App\Http\Controllers\Rooms\ChangeRoomController;
use App\Http\Controllers\Properties\ManajementPropertiesController;
use App\Http\Controllers\Properties\ManajementRoomsController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\RefundController;
use Symfony\Component\Console\Command\CompleteCommand;

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
    Route::get('/progress', [DashboardController::class, 'progress_index'])->name('progress');

    Route::get('/test/images', [DashboardController::class, 'testImages'])->name('test.images');
    Route::get('/test/image/{id}', [DashboardController::class, 'testSingleImage'])->name('test.image');

    Route::get('/account/getData', [UserController::class, 'accountGetData'])->name('account.getData');
    Route::get('/users/getList', [UserController::class, 'userGetData'])->name('users.getList');
    Route::get('/users/getMainMenus', [UserController::class, 'usergetMainMenus'])->name('menus.getMainMenus');

    Route::get('/menus/getData', [UserController::class, 'getData'])->name('menus.getData');
    Route::get('/menus/getUserAccess', [UserController::class, 'getUserAccess'])->name('menus.getUserAccess');

    Route::get('/settings/users-management', [UserController::class, 'index'])->name('users-management');
    Route::get('/settings/users-management/show', [UserController::class, 'show'])->name('users.show');

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
        Route::get('/bookings', [AllBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/filter', [AllBookingController::class, 'filter'])->name('bookings.filter');

        Route::get('/pendings', [PendingController::class, 'index'])->name('pendings.index');
        Route::get('/pendings/filter', [PendingController::class, 'filter'])->name('pendings.filter');

        Route::get('/newReserv', [NewReservController::class, 'index'])->name('newReserv.index');
        Route::get('/newReserv/filter', [NewReservController::class, 'filter'])->name('newReserv.filter');
        Route::post('/newReserv/{order_id}', [NewReservController::class, 'checkIn'])->name('newReserv.checkin');
        Route::get('/newReserv-in/{order_id}/details', [NewReservController::class, 'getBookingDetails'])->name('newReserv.checkin.details');

        Route::get('/completed', [CompletedController::class, 'index'])->name('completed.index');
        Route::get('/completed/filter', [CompletedController::class, 'filter'])->name('completed.filter');

        Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
        Route::get('/checkin/filter', [CheckInController::class, 'filter'])->name('checkin.filter');
        // ---------------------------------------------------------------------------------------------------------------------
        Route::post('/checkin/{order_id}', [CheckInController::class, 'checkIn'])->name('bookings.checkin');
        Route::get('/check-in/{order_id}/details', [CheckInController::class, 'getBookingDetails'])->name('bookings.checkin.details');

        Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout.index');
        Route::get('/checkout/filter', [CheckOutController::class, 'filter'])->name('checkout.filter');
        Route::post('/check-out/{order_id}', [CheckOutController::class, 'checkOut'])->name('bookings.checkout');
        Route::get('/check-out/{order_id}/details', [CheckOutController::class, 'getBookingDetails'])->name('bookings.checkin.details');
    });

    Route::prefix('rooms')->group(function () {
        Route::get('/change-room', [ChangeRoomController::class, 'index'])->name('changerooom.index');
        Route::get('/change-room/available-rooms', [ChangeRoomController::class, 'getAvailableRooms']);
        Route::post('/change-room/store', [ChangeRoomController::class, 'store'])->name('changeroom.store');
    });

    Route::prefix('properties')->group(function () {
        Route::get('/m-properties', [ManajementPropertiesController::class, 'index'])->name('properties.index');
        Route::post('/m-properties/filter', [ManajementPropertiesController::class, 'filter'])->name('properties.filter');
        Route::post('/properties/toggle-status', [ManajementPropertiesController::class, 'toggleStatus'])->name('properties.toggle-status');

        Route::put('/m-properties/{property}/status', [ManajementPropertiesController::class, 'updateStatus'])->name('properties.updateStatus');
        Route::post('/m-properties/store', [ManajementPropertiesController::class, 'store'])->name('properties.store');
        Route::put('/m-properties/update/{idrec}', [ManajementPropertiesController::class, 'update'])->name('properties.update');
        Route::get('/m-properties/table', [ManajementPropertiesController::class, 'tablePartial'])->name('properties.table');

        Route::get('/m-properties/facility', [ManajementPropertiesController::class, 'indexFacility'])->name('facilityProperty.index');
        Route::post('/m-properties/facility/store', [ManajementPropertiesController::class, 'storeFacility'])->name('facilityProperty.store');
        Route::put('/m-properties/facility/update/{id}', [ManajementPropertiesController::class, 'updateFacility'])->name('facilityProperty.update');

        // ------------------------- ROOMS MANAGEMENT -------------------------
        Route::get('/m-rooms', [ManajementRoomsController::class, 'index'])->name('rooms.index');
        Route::post('/rooms/store', [ManajementRoomsController::class, 'store'])->name('rooms.store');
        Route::post('/rooms/check-room-number', [ManajementRoomsController::class, 'checkRoomNumber'])->name('rooms.check-room-number');
        Route::put('/rooms/update/{idrec}', [ManajementRoomsController::class, 'update'])->name('rooms.update');
        Route::put('/rooms/{room}/status', [ManajementRoomsController::class, 'updateStatus'])->name('room.updateStatus');
        Route::get('/rooms/{id}', [ManajementRoomsController::class, 'show'])->where('id', '[0-9]+')->name('rooms.show');
        Route::get('/rooms/table', [ManajementRoomsController::class, 'tablePartial'])->name('properties.table');
        Route::delete('/rooms/{idrec}/destroy', [ManajementRoomsController::class, 'destroy'])->name('rooms.destoy');
        Route::get('/rooms/{room}/edit-prices', [ManajementRoomsController::class, 'changePriceIndex'])->name('rooms.prices.change-price-index');
        Route::get('/rooms/{room}/price', [ManajementRoomsController::class, 'getPriceForDate'])->name('rooms.prices.date');
        Route::post('/rooms/{room}/update-price', [ManajementRoomsController::class, 'updatePriceRange'])->name('rooms.prices.update');
        Route::get('/rooms/{room}/prices', [ManajementRoomsController::class, 'getRoomPrices'])->name('rooms.prices.index');

        Route::get('/m-rooms/facilityRooms', [ManajementRoomsController::class, 'indexFacility'])->name('facilityRooms.index');
        Route::post('/rooms/facilityRooms/store', [ManajementRoomsController::class, 'storeFacility'])->name('facilityRooms.store');
        Route::put('/rooms/facilityRooms/update/{id}', [ManajementRoomsController::class, 'updateFacility'])->name('facilityRooms.update');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/pay', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/payments/filter', [PaymentController::class, 'filter'])->name('admin.payments.filter');
        Route::post('/approve/{id}', [PaymentController::class, 'approve'])->name('admin.payments.approve');
        Route::post('/reject/{id}', [PaymentController::class, 'reject'])->name('admin.payments.reject');
        Route::put('/cancel/{id}', [PaymentController::class, 'cancel'])->name('admin.bookings.cancel');

        Route::get('/refund', [RefundController::class, 'index'])->name('admin.refunds.index');
        Route::post('/refund/store', [RefundController::class, 'store'])->name('admin.refunds.store');
        Route::post('/refund/cancel/{id_booking}', [RefundController::class, 'cancel'])->name('admin.refunds.cancel');
    });
});

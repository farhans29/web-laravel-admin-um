<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountSettings\UserSettingsController;
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
use App\Http\Controllers\RoomAvailability\RoomAvailabilityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Reports\BookingReportController;
use App\Http\Controllers\Reports\PaymentReportController;
use App\Http\Controllers\Reports\RentedRoomsReportController;
use App\Http\Controllers\VoucherController;
use Symfony\Component\Console\Command\CompleteCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


// Route::redirect('/', 'login');

Route::middleware(['guest'])->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('login');
    Route::post('/', [SessionController::class, 'login']);
});
Route::get('/home', function () {
    return redirect('/dashboard');
});

Route::get('storage/{path}', function ($path) {
    // Remove any URL parameters if present
    $path = explode('?', $path)[0];

    // Build the full file path
    $filePath = storage_path('app/public/' . ltrim($path, '/'));

    if (!File::exists($filePath)) {
        abort(404, 'File not found');
    }

    try {
        $file = File::get($filePath);
        $type = File::mimeType($filePath);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Cache-Control", "public, max-age=31536000, immutable");

        return $response;
    } catch (\Exception $e) {
        abort(500, 'Error serving file');
    }
})->where('path', '.*');

Route::middleware(['auth', 'permission'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/room-report', [DashboardController::class, 'getPropertyRoomReport']);
    Route::get('/dashboard/room-report/{propertyId}', [DashboardController::class, 'getPropertyRoomReport']);
    Route::get('/progress', [DashboardController::class, 'progress_index'])->name('progress');

    Route::get('/account/getData', [UserController::class, 'accountGetData'])->name('account.getData');
    Route::get('/users/getList', [UserController::class, 'userGetData'])->name('users.getList');
    Route::get('/users/getMainMenus', [UserController::class, 'usergetMainMenus'])->name('menus.getMainMenus');

    Route::get('/menus/getData', [UserController::class, 'getData'])->name('menus.getData');
    Route::get('/menus/getUserAccess', [UserController::class, 'getUserAccess'])->name('menus.getUserAccess');

    Route::get('/settings/users-management', [UserController::class, 'index'])->name('users-management');
    Route::get('/settings/users-management/show', [UserController::class, 'show'])->name('users.show');

    Route::get('/settings/users-management/new', [UserController::class, 'indexNew'])->name('users-newManagement');
    Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');
    Route::post('/settings/users-management/new', [UserController::class, 'store'])->name('users.store');

    Route::get('/settings/users-management/edit', [UserController::class, 'indexEdit'])->name('users-editManagement');
    Route::put('/settings/users/{user}', [UserController::class, 'updateUsers'])->name('users.update');
    Route::put('/settings/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

    Route::get('/settings/users-management/delete', [UserController::class, 'indexDelete'])->name('users-deleteManagement');
    Route::post('/settings/users/{user}/deactivate', [UserController::class, 'deactivateUser'])->name('users.deactivate');


    Route::get('/settings/users-access-management', [UserController::class, 'indexUserAccessManagement'])->name('users-access-management');
    Route::get('/user-access/edit', [UserController::class, 'indexUserAccessManagement'])->name('user-access.edit');
    Route::get('/user-access/{userId}/permissions', [UserController::class, 'getUserPermissions']);
    Route::post('/user-access/{userId}/update', [UserController::class, 'update']);

    // Master Role Management Routes
    Route::get('/settings/master-role-management', [UserController::class, 'indexMasterRole'])->name('master-role-management');
    Route::post('/master-role/create', [UserController::class, 'createRole'])->name('master-role.create');
    Route::post('/master-role/update/{userId}', [UserController::class, 'updateMasterRole'])->name('master-role.update');
    Route::get('/master-role/permissions/{userId}', [UserController::class, 'getMasterRolePermissions'])->name('master-role.permissions');
    Route::post('/master-role/update-permissions/{userId}', [UserController::class, 'updateMasterRolePermissions'])->name('master-role.update-permissions');

    // User Settings Routes
    Route::put('/user/password', [UserSettingsController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/user/activity', [UserSettingsController::class, 'getUserActivity'])->name('user.activity');

    Route::prefix('bookings')->group(function () {
        Route::get('/bookings', [AllBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/filter', [AllBookingController::class, 'filter'])->name('bookings.filter');

        Route::get('/pendings', [PendingController::class, 'index'])->name('pendings.index');
        Route::get('/pendings/filter', [PendingController::class, 'filter'])->name('pendings.filter');

        Route::get('/newReserv', [NewReservController::class, 'index'])->name('newReserv.index');
        Route::get('/newReserv/filter', [NewReservController::class, 'filter'])->name('newReserv.filter');
        Route::post('/newReserv/{order_id}', [NewReservController::class, 'checkIn'])->name('newReserv.checkin');
        Route::get('/newReserv-in/{order_id}/details', [NewReservController::class, 'getBookingDetails'])->name('newReserv.checkin.details');

        Route::get('/newReserv-in/{order_id}/regist', [NewReservController::class, 'getRegist'])->name('newReserv.checkin.regist');
        Route::get('/newReserv-in/{order_id}/invoice', [NewReservController::class, 'getInvoice'])->name('newReserv.checkin.invoice');

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

        // ---------------------------------------------------------------------------------------------------------------------

        Route::get('/room-availability', [RoomAvailabilityController::class, 'index'])->name('room-availability.index');
        Route::get('/room-availability/data', [RoomAvailabilityController::class, 'getAvailabilityData'])->name('room-availability.data');
        Route::post('/room-availability/{id}/status', [RoomAvailabilityController::class, 'updateRentalStatus'])->name('room-availability.update-status');
        Route::get('/room-availability/{room}/bookings', [RoomAvailabilityController::class, 'getRoomBookings'])->name('room-availability.bookings');
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

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/filter', [CustomerController::class, 'filter'])->name('customers.filter');
        Route::get('/{identifier}/bookings', [CustomerController::class, 'getBookings'])->name('customers.bookings');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/booking-report', [BookingReportController::class, 'index'])->name('reports.booking.index');
        Route::get('/booking-report/data', [BookingReportController::class, 'getData'])->name('reports.booking.data');
        Route::get('/booking-report/export', [BookingReportController::class, 'export'])->name('reports.booking.export');

        Route::get('/payment-report', [PaymentReportController::class, 'index'])->name('reports.payment.index');
        Route::get('/payment-report/data', [PaymentReportController::class, 'getData'])->name('reports.payment.data');
        Route::get('/payment-report/export', [PaymentReportController::class, 'export'])->name('reports.payment.export');

        Route::get('/rented-rooms-report', [RentedRoomsReportController::class, 'index'])->name('reports.rented-rooms.index');
        Route::get('/rented-rooms-report/data', [RentedRoomsReportController::class, 'getData'])->name('reports.rented-rooms.data');
        Route::get('/rented-rooms-report/export', [RentedRoomsReportController::class, 'export'])->name('reports.rented-rooms.export');
    });

    Route::prefix('vouchers')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::get('/filter', [VoucherController::class, 'filter'])->name('vouchers.filter');
        Route::post('/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle-status');
        Route::post('/store', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('/{id}', [VoucherController::class, 'show'])->where('id', '[0-9]+')->name('vouchers.show');
        Route::put('/{id}', [VoucherController::class, 'update'])->where('id', '[0-9]+')->name('vouchers.update');
        Route::delete('/{id}', [VoucherController::class, 'destroy'])->where('id', '[0-9]+')->name('vouchers.destroy');
    });
});

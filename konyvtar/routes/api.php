<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Librarian;
use App\Http\Middleware\Warehouseman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// bárki által elérhető
Route::post('/register',[RegisteredUserController::class, 'store']);
Route::post('/login',[AuthenticatedSessionController::class, 'store']);

// összes kérés
Route::patch('update-password/{id}', [UserController::class, "updatePassword"]);

// autentikált útvonal, simple user is
Route::middleware(['auth:sanctum'])
    ->group(function () {
        // profil elérése, módosítása
        Route::get('/auth-user',[UserController::class, 'update']);
        Route::get('/auth-user',[UserController::class, 'show']);
        // Hány kölcsönzése volt idáig a bejelentkezett felhasználónak:
        Route::get('/lendings-count', [LendingController::class, 'lendingCount']);
        // Hány aktív kölcsönzése van
        Route::get('/active-lendings-count', [LendingController::class, 'activelendingCount']);
        // Hány k9nyvet kölcsönzött idáig
        Route::get('/lendings-books-count', [LendingController::class, 'lendingsBooksCount']);
        //kikölcsönzött könyvek adatai:
        Route::get('/lendings-books-data', [LendingController::class, 'lendingsBooksData']);
        
        Route::get('/lendings-copies',[LendingController::class, 'lendingsWithCopies']);
        Route::get('/userLend',[UserController::class, 'userLendings']);
        Route::get('/reserved-books', [ReservationController::class, 'reservedBooks']);
        Route::get('/reserved-count', [ReservationController::class, 'reservedCount']);
        Route::get('/reservations-i-have-from', [LendingController::class, 'reservationsIHaveFrom']);
        Route::patch('/bring-back/{copy_id}/{start}', [LendingController::class, 'bringBack']);
        Route::patch('/bring-back2/{copy_id}/{start}', [LendingController::class, 'bringBack2']);

        // Kijelentkezés útvonal
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    });

// admin útvonal
Route::middleware(['auth:sanctum',Admin::class])
->group(function () {
    // összes kérés
    Route::apiResource('/admin/users', UserController::class);
    Route::get('/admin/specific-date',[LendingController::class, 'dateSpecific']);
    Route::get('/admin/specific-copy/{copy_id}',[LendingController::class, 'copySpecific']);
});

// librarian útvonal
Route::middleware(['auth:sanctum',Librarian::class])
->group(function () {
    // útvonalak
    Route::get('/librarian/books-copies',[BookController::class, 'booksWithCopies']);
    //Route::apiResource('/librarian/reservations',ReservationController::class);
    Route::get('/librarian/reservations',[ReservationController::class, 'index']);
    Route::get('/librarian/reservations/{user_id}/{book_id}/{start}',[ReservationController::class, 'show']);
    Route::patch('/librarian/reservations/{user_id}/{book_id}/{start}',[ReservationController::class, 'update']);
    Route::get('/librarian/users-and-reservations', [UserController::class, 'usersAndReservations']);
});

// raktáros útvonal
Route::middleware(['auth:sanctum',Warehouseman::class])
->group(function () {
    // útvonalak
});
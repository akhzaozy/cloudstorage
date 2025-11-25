<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

//
// Redirect root ke Drive
//
Route::get('/', function () {
    return redirect('/drive');
});

//
// AUTH ROUTES (Login dan Logout)
// Breeze biasanya arahkan ke dashboard, tapi kita ganti ke login / drive
//
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

//
// DRIVE ROUTES
//

// halaman utama drive
Route::get('/drive', [DriveController::class, 'index']);

// buka folder
Route::get('/drive/folder/{id}', [DriveController::class, 'openFolder']);

// upload file
Route::post('/drive/upload', [DriveController::class, 'uploadFile']);

// buat folder
Route::post('/drive/folder', [DriveController::class, 'createFolder']);

// rename file/folder
Route::post('/drive/rename/{id}', [DriveController::class, 'rename']);

// move file/folder
Route::post('/drive/move/{id}', [DriveController::class, 'move']);

// halaman Trash
Route::get('/drive/trash', [DriveController::class, 'trash']);

// restore item dari trash
Route::post('/drive/restore/{id}', [DriveController::class, 'restore']);

// soft delete (ke trash)
Route::delete('/drive/delete/{id}', [DriveController::class, 'softDelete']);

// force delete (hapus permanen)
Route::delete('/drive/force-delete/{id}', [DriveController::class, 'forceDelete']);

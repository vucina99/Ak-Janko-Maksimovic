<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
//        Institutions
    Route::get('/institutions', [AdminController::class, 'institutions']);
    Route::get('/get/type', [AdminController::class, 'getType']);
    Route::get('/get/institutions/{page}', [AdminController::class, 'getInstitutions']);
    Route::post('/create/institution', [AdminController::class, 'createInstitution']);
    Route::patch('update/activation/intitution/{id}' ,  [AdminController::class, 'updateActivationInstitution']);


//        Staff
    Route::get('/staff', [AdminController::class, 'staff']);
    Route::post('/create/staff', [AdminController::class, 'createUser']);
    Route::get('/get/users', [AdminController::class, 'getUsers']);
    Route::delete('/delete/user/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/change-role/{id}', [AdminController::class, 'changeRole']);




//        Folders
    Route::get('/folders', [AdminController::class, 'folders']);
    Route::delete('/delete/folder/{id}', [AdminController::class, 'deleteFolder']);
    Route::post('/create/folder', [AdminController::class, 'createFolder']);


});
Route::get('admin/get/folders', [AdminController::class, 'getFolders']);

<?php

use App\Http\Controllers\TrialController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'trial'], function () {
    Route::get('/get/institutions', [TrialController::class, 'getInstitutions']);
    Route::get('/get/users', [TrialController::class, 'getUsers']);
    Route::post('/create/trial', [TrialController::class, 'createTrial']);
    Route::post('/get/trials', [TrialController::class, 'getTrials']);
    Route::post('/get/persons', [TrialController::class, 'getPersons']);
    Route::patch('/edit/trial/{id}', [TrialController::class, 'updateTrial'])->middleware("adminHttp");;
    Route::delete('/delete/trial/{id}', [TrialController::class, 'deleteTrial'])->middleware("adminHttp");;

    Route::post('/change-visit', [TrialController::class, 'changeVisit']);
    Route::get('/export-pdf/{date}', [TrialController::class, 'exportPdf']);
    Route::post('/get-trial-search', [TrialController::class, 'getSearchTrial']);

});

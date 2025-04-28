<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignDonationController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/token', [AuthController::class, 'issueToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::get('/campaigns/{id}', [CampaignController::class, 'show']);
    Route::get('/campaigns/{id}/donations', [CampaignDonationController::class, 'index']);
    Route::post('/campaigns', [CampaignController::class, 'store']);

    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations', [DonationController::class, 'store']);
});

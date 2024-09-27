<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\DonationCampaignController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as' => 'api.'
], function () {
    # ============== AUTH ROUTES ==============
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('auth/register', [AuthController::class, 'register'])->name('register');

    # ============== CATEGORIES ROUTES ==============
    Route::get('categories', CategoryController::class)->name('categories.index');

    # ============== DONATIONS ROUTES ==============
    Route::get('donation-campaigns', [DonationCampaignController::class, 'index'])
        ->name('donation_campaigns.index');
    Route::get('donation-campaigns/{campaign}', [DonationCampaignController::class, 'show'])
        ->name('donation_campaigns.show');
    Route::get('donation-campaigns/{campaign}/donations', [DonationCampaignController::class, 'donations'])
        ->name('donation_campaigns.donations');
    Route::post('donation-campaigns/{campaign}/donate', [DonationCampaignController::class, 'donate'])
        ->name('donation_campaigns.donate');

    // ======================================
    // API FOR AUTHENTICATED USERS
    // ======================================
    Route::group([
        'prefix' => 'users.console',
        'middleware' => ['auth:sanctum']
    ], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'getAuthenticatedUser'])->name('me');

        Route::post('donation-campaigns', [DonationCampaignController::class, 'store'])
            ->name('donation_campaigns.store');
    });
 });

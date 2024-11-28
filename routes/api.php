<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TypeTransactionController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::apiResource('clients', ClientController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('type-transactions', TypeTransactionController::class);

// OTP Management
Route::post('otp/send', [OtpController::class, 'sendOtp']);
Route::post('otp/verify', [OtpController::class, 'verifyOtp']);

// Verification APIs
Route::post('verify/phone', [VerificationController::class, 'verifyPhoneNumber']);
Route::post('verify/cnib', [VerificationController::class, 'verifyCnib']);
Route::post('/verify/transaction', [VerificationController::class, 'verifyTransaction']);

// Password reset
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post('/clients/upload-selfie', [ClientController::class, 'uploadSelfie']);
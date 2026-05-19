<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentCallbackController;

Route::post('/payment/callback', [PaymentCallbackController::class, 'handle']);

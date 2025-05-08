<?php

use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/print/delivery/{id}', [PrintController::class, 'deliveryPrint'])->name('print.delivery');
});
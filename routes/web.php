<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::redirect('/', '/contact/create');

Route::get('/contact/create', [ContactController::class, "create"])
    ->name('contact.create');

Route::post('/contact/store', [ContactController::class, "store"])
    ->name('contact.store')
    ->middleware('contact.validator');

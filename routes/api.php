<?php

use AmoCRM\Client\AmoCRMApiClient;
use App\Services\Helpers\ConfigHelper;
use App\Services\TokenSaver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AmoCRM\GetOAuthCredentialsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/get-credentials', [GetOAuthCredentialsController::class, 'getCredentials']);
Route::get('dev', function () {
    $apiClient = new AmoCRMApiClient(...ConfigHelper::getAmoCRMClientConfig());
    $apiClient->setAccountBaseDomain(config('amoCRM.base_domain'));
    $apiClient->setAccessToken(TokenSaver::restore());

    dd($apiClient->catalogs()->getOne(6523));
});

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Services\Helpers\ConfigHelper;
use App\Services\TokenSaver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GetOAuthCredentialsController
{
    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     */
    public function getCredentials(Request $request) : \Illuminate\Http\RedirectResponse
    {
        $code = $request->query('code');

        $apiClient = new AmoCRMApiClient(...ConfigHelper::getAmoCRMClientConfig());

        $apiClient->setAccountBaseDomain(config('amoCRM.base_domain'));

        $tokenData = $apiClient->getOAuthClient()->getAccessTokenByCode($code);
        TokenSaver::save($tokenData);

        return to_route('contact.create');
    }
}

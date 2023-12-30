<?php

namespace App\Http\Controllers\Api\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Services\Helper;
use App\Services\TokenSaver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GetOAuthCredentialsController
{
    /**
     * @throws AmoCRMoAuthApiException
     */
    public function getCredentials(Request $request) : \Illuminate\Http\RedirectResponse
    {
        $code = $request->query('code');

        $appClient = new AmoCRMApiClient(...Helper::getAmoCRMClientConfig());

        $appClient->setAccountBaseDomain(config('amoCRM.base_domain'));

        $tokenData = $appClient->getOAuthClient()->getAccessTokenByCode($code);
        TokenSaver::save($tokenData);
        return to_route('contact.create');
    }
}

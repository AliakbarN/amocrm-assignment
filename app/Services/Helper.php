<?php

namespace App\Services;

class Helper
{
    public static function getAmoCRMClientConfig() :array
    {
        return [
            config('amoCRM.client_id'),
            config('amoCRM.client_secret'),
            config('amoCRM.client_redirect_url')
        ];
    }
}

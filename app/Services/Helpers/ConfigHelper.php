<?php
declare(strict_types=1);

namespace App\Services\Helpers;

class ConfigHelper
{
    public static function getAmoCRMClientConfig() :array
    {
        return [
            env('AMOCRM_CLIENT_ID'),
            env('AMOCRM_CLIENT_SECRET'),
            config('amoCRM.client_redirect_url')
        ];
    }
}

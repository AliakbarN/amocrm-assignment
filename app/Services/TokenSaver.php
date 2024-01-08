<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class TokenSaver
{
    protected static string $fileName = 'access_token';

    protected static function generatePathToFile() :string
    {
        return 'amoCRM/' . self::$fileName . '.json';
    }
    public static function save(AccessTokenInterface $token) :void
    {
        $data = json_encode($token->jsonSerialize(), JSON_PRETTY_PRINT);
        Storage::disk('local')->put(self::generatePathToFile(), $data);
    }

    public static function restore() :AccessTokenInterface
    {
        // Read stored data from the file
        $data = json_decode(Storage::disk('local')->get(self::generatePathToFile()), 1);
        return new AccessToken($data);
    }
}

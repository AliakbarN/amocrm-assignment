<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactFormValidator
{

    private const ERROR_STATUS_CODE = 422;

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = $request->all();

        foreach ($data as $field => $value)
        {
            if (trim($value) === '') {
                return new Response('Validation was failed', self::ERROR_STATUS_CODE);
            }
        }

        return $next($request);
    }
}

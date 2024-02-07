<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactFormValidator
{
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
                return new Response('Validation was failed', StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY);
            }
        }

        return $next($request);
    }
}

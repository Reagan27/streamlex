<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class HandleDatabaseErrors
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (QueryException $e) {
            // Check for duplicate entry error (MySQL error code 1062)
            if ($e->getCode() === '23000' && str_contains($e->getMessage(), 'Duplicate entry')) {
                preg_match("/Duplicate entry '(.+)' for key '(.+)'/", $e->getMessage(), $matches);
                
                if (count($matches) >= 3) {
                    $value = $matches[1];
                    $key = $matches[2];
                    
                    // Create user-friendly error messages
                    $errorMessages = [
                        'users.users_email_unique' => "The email address '{$value}' is already registered. Please use a different email address.",
                        'users.users_phone_unique' => "The phone number '{$value}' is already registered. Please use a different phone number.",
                        'default' => "This {$key} already exists in our records. Please try a different value."
                    ];
                    
                    $message = $errorMessages[$key] ?? $errorMessages['default'];
                    
                    return redirect()->back()
                        ->withInput()
                        ->withErrors([$key => $message]);
                }
            }
            
            throw $e;
        }
    }
}
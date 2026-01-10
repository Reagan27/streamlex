<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Log\LogManager;
use Monolog\Handler\NullHandler;

class DisableLogging
{
    public function handle($request, Closure $next)
    {
        $app = app();
        
        // Force null handler
        $app->singleton('log', function () use ($app) {
            $logger = new LogManager($app);
            $logger->setDefaultDriver('null');
            return $logger;
        });

        // Replace existing log instance
        if ($app->resolved('log')) {
            $app->forgetInstance('log');
            $app->make('log');
        }

        return $next($request);
    }
}

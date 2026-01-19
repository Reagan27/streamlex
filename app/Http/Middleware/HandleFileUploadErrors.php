<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\PostTooLargeException;

class HandleFileUploadErrors
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (PostTooLargeException $e) {
            if ($request->is('onboarding/documents')) {
                $errorField = $this->determineErrorField($request);
                return redirect()->back()->withErrors([
                    $errorField => 'The uploaded file is too large. Maximum allowed size is 32MB.'
                ])->withInput();
            }
            
            throw $e;
        }
    }

    private function determineErrorField(Request $request): string
    {
        $errorField = 'upload_error';
        $contentLength = $request->header('Content-Length');
        if ($contentLength > 33554432) {
            if (str_contains($request->header('Content-Disposition') ?? '', 'id_photo')) {
                $errorField = 'id_photo';
            } elseif (str_contains($request->header('Content-Disposition') ?? '', 'kra_certificate')) {
                $errorField = 'kra_certificate';
            }
        }
        
        return $errorField;
    }
}
<?php

namespace Spatie\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class DefaultLogWriter implements LogWriter
{
    public function logRequestResponse(Request $request, Response $response)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        // $files = (new Collection(iterator_to_array($request->files)))
        //     ->map([$this, 'flatFiles'])
        //     ->flatten()
        //     ->implode(',');

        $message = "{$method} {$uri}"; //" - RequestBody: {$bodyAsJson} - Files: " . $files;
        $context = [];
        if (config('http-logger.log_request_body', false)) {
            $context['request'] = $bodyAsJson;
        }
        if (config('http-logger.auth_user_id', false) && auth()->id()) {
            $context['user'] = auth()->id();
        }
        if (config('http-logger.log_response', false)) {
            $statusCode = $response->getStatusCode();
            $message .= " $statusCode";
        }
        if (config('http-logger.log_response_body', false)) {
            $context['response'] = $response->getContent();
            $context['headers'] = json_encode($response->headers);
        }

        Log::info($message, $context);
    }

    public function flatFiles($file)
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalName();
        }
        if (is_array($file)) {
            return array_map([$this, 'flatFiles'], $file);
        }
        return (string) $file;
    }
}

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

        if (config('http-logger.auth_user_id', false) && auth()->id()) {
            $message .= ' { "user": ' . auth()->id() . ' }';
        }

        if (config('http-logger.log_response', false)) {
            // $responseBodyAsJson = $response->getContent();
            $statusCode = $response->getStatusCode();
            // $responseHeaderAsJson = json_encode($response->headers);

            // $message .= "HttpStatus: $statusCode - ResponseBody: $responseBodyAsJson - Header: $responseHeaderAsJson";
            $message .= " $statusCode";
        }

        Log::channel('httplogger')->info($message);
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

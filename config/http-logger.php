<?php

return [

    /*
     * The log profile which determines whether a request should be logged.
     * It should implement `LogProfile`.
     */
    'log_profile' => env('HTTPLOGGER_LOGGER_CLASS', \Spatie\HttpLogger\LogNonGetRequests::class),

    /*
     * The log writer used to write the request to a log.
     * It should implement `LogWriter`.
     */
    'log_writer' => \Spatie\HttpLogger\DefaultLogWriter::class,

    /*
     * Filter out body fields which will never be logged.
     */
    'except' => [
        'password',
        'password_confirmation',
    ],

    /*
     * The log response is an options to decide log response or not
     */
    'log_response' => env('HTTPLOGGER_RESPONSE', true),,
    'log_response_body' => env('HTTPLOGGER_RESPONSE_BODY', false),
    'log_request_body' => env('HTTPLOGGER_REQUEST_BODY', false),

    /*
     * Log only the methods configured here
     * options: 'get', 'post', 'put', 'patch', 'delete', 'head', 'options', 'trace'
     */
    'log_method' => [
        'post', 'put', 'patch', 'delete', 'get',
    ],

    /*
     * Log user id
     */
    'auth_user_id' => true
];

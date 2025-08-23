<?php

return [
    /*
     * You can enable CORS for 1 or multiple paths.
     * Example: ['api/*', 'sanctum/csrf-cookie']
     */
    'paths' => ['*'], // Cho phép tất cả routes

    /*
     * Matches the request method. `[*]` allows all methods.
     */
    'allowed_methods' => ['*'],

    /*
     * Matches the request origin. `[*]` allows all origins.
     */
    'allowed_origins' => ['*'],

    /*
     * Patterns that can be used with `preg_match` to match the origin.
     */
    'allowed_origins_patterns' => [],

    /*
     * Sets the Access-Control-Allow-Headers response header.
     */
    'allowed_headers' => ['*'],

    /*
     * Sets the Access-Control-Expose-Headers response header.
     */
    'exposed_headers' => [],

    /*
     * Sets the Access-Control-Max-Age response header.
     */
    'max_age' => 0,

    /*
     * Sets the Access-Control-Allow-Credentials header.
     */
    'supports_credentials' => false,
];

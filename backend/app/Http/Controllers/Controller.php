<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Better ERP API',
    version: '1.0.0',
    description: 'API documentation for the Better ERP backend.',
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'API server',
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    description: 'Sanctum personal access token. Enter the raw token issued by /auth/login.',
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: false),
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            nullable: true,
            example: ['email' => ['The email field is required.']],
        ),
        new OA\Property(property: 'debug', type: 'object', nullable: true),
    ],
)]
abstract class Controller
{
    //
}

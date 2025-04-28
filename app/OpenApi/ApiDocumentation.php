<?php

namespace App\OpenApi;

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="CSR Donation API",
 *     description="API documentation for the internal CSR donation platform",
 *
 *     @OA\Contact(
 *         email="paul.schiller@email.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ApiDocumentation {}

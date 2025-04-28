<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\UseCases\IssueAuthToken\IssueAuthTokenHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

final class AuthController extends Controller
{
    public function __construct(
        private readonly IssueAuthTokenHandler $handler
    ) {}

    /**
     * @OA\Post(
     *     path="/api/auth/token",
     *     tags={"Authentication"},
     *     summary="Authenticate employee and issue access token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="paul@acme.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="token", type="string", example="1|abc123tokenvalue")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function issueToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $token = $this->handler->handle($request->email, $request->password);

            return response()->json(['token' => $token]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}

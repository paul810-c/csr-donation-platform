<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\UseCases\CreateDonation\CreateDonationHandler;
use App\Application\UseCases\ListEmployeeDonations\ListEmployeeDonationsHandler;
use App\Domain\Common\ValueObject\Money;
use App\Http\Requests\StoreDonationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

final class DonationController extends Controller
{
    public function __construct(
        private readonly CreateDonationHandler $handler,
        private readonly ListEmployeeDonationsHandler $listHandler
    ) {}

    /**
     * @OA\Get(
     *     path="/api/donations",
     *     tags={"Donations"},
     *     summary="List all donations made by the authenticated employee",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="campaign_id", type="integer"),
     *                 @OA\Property(property="employee_id", type="integer"),
     *                 @OA\Property(property="amount", type="string", example="25.00"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ))
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $employee = $this->authenticatedEmployee();

        $donations = $this->listHandler->handle($employee->id);

        return response()->json([
            'data' => array_map(fn ($d) => [
                'id' => $d->id,
                'campaign_id' => $d->campaignId,
                'employee_id' => $d->employeeId,
                'amount' => $d->amount->toString(),
                'created_at' => $d->createdAt->format(DATE_ATOM),
            ], $donations),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/donations",
     *     tags={"Donations"},
     *     summary="Create a new donation to a campaign",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"campaign_id", "amount"},
     *
     *             @OA\Property(property="campaign_id", type="integer", example=1),
     *             @OA\Property(property="amount", type="string", example="50.00")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Donation created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="campaign_id", type="integer"),
     *             @OA\Property(property="employee_id", type="integer"),
     *             @OA\Property(property="amount", type="string", example="50.00"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreDonationRequest $request): JsonResponse
    {
        $employee = $this->authenticatedEmployee();

        $donation = $this->handler->handle(
            campaignId: $request->input('campaign_id'),
            employeeId: $employee->id,
            amount: Money::fromString($request->input('amount'))
        );

        return response()->json([
            'id' => $donation->id,
            'campaign_id' => $donation->campaignId,
            'employee_id' => $donation->employeeId,
            'amount' => $donation->amount->toString(),
            'created_at' => $donation->createdAt->format(DATE_ATOM),
        ], 201);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\UseCases\ListCampaignDonations\ListCampaignDonationsHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

final class CampaignDonationController extends Controller
{
    public function __construct(
        private readonly ListCampaignDonationsHandler $handler
    ) {}

    /**
     * @OA\Get(
     *     path="/api/campaigns/{campaignId}/donations",
     *     tags={"Campaign Donations"},
     *     summary="List all donations for a given campaign",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="campaignId",
     *         in="path",
     *         required=true,
     *         description="id of the campaign to list donations for",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="campaign_id", type="integer"),
     *                 @OA\Property(property="employee_id", type="integer"),
     *                 @OA\Property(property="amount", type="string", example="50.00"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Campaign not found"
     *     )
     * )
     */
    public function index(Request $request, int $campaignId): JsonResponse
    {
        $donations = $this->handler->handle($campaignId);

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
}

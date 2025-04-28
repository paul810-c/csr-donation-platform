<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\UseCases\CreateCampaign\CreateCampaignHandler;
use App\Application\UseCases\GetCampaignWithTotals\GetCampaignWithTotalsHandler;
use App\Application\UseCases\ListCampaigns\ListCampaignsHandler;
use App\Domain\Common\ValueObject\Money;
use App\Http\Requests\StoreCampaignRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

final class CampaignController extends Controller
{
    public function __construct(
        private readonly CreateCampaignHandler $createCampaignHandler,
        private readonly ListCampaignsHandler $listCampaignsHandler,
        private readonly GetCampaignWithTotalsHandler $getCampaignHandler
    ) {}

    /**
     * @OA\Get(
     *     path="/api/campaigns",
     *     tags={"Campaigns"},
     *     summary="List all campaigns owned by the authenticated employee",
     *     security={{"bearerAuth":{}}},
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
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="goal_amount", type="string"),
     *                 @OA\Property(property="owner_id", type="integer"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             ))
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $employee = $this->authenticatedEmployee();

        $campaigns = $this->listCampaignsHandler->handle($employee->id);

        return response()->json([
            'data' => array_map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'description' => $c->description,
                'goal_amount' => $c->goalAmount->toString(),
                'owner_id' => $c->ownerId,
                'status' => $c->status->value,
                'created_at' => $c->createdAt->format(DATE_ATOM),
            ], $campaigns),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/campaigns/{id}",
     *     tags={"Campaigns"},
     *     summary="Get a campaign by id, including total donations",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="id of the campaign",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="goal_amount", type="string"),
     *             @OA\Property(property="current_amount", type="string"),
     *             @OA\Property(property="owner_id", type="integer"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Campaign not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $data = $this->getCampaignHandler->handle($id);

        if (! $data) {
            return response()->json(['message' => 'Campaign not found.'], 404);
        }

        return response()->json($data);
    }

    /**
     * @OA\Post(
     *     path="/api/campaigns",
     *     tags={"Campaigns"},
     *     summary="Create a new campaign",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title", "description", "goal_amount"},
     *
     *             @OA\Property(property="title", type="string", example="Recycle more"),
     *             @OA\Property(property="description", type="string", example="Recycle more"),
     *             @OA\Property(property="goal_amount", type="string", example="1000.00")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Campaign created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="goal_amount", type="string"),
     *             @OA\Property(property="owner_id", type="integer"),
     *             @OA\Property(property="status", type="string"),
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
    public function store(StoreCampaignRequest $request): JsonResponse
    {
        $employee = $this->authenticatedEmployee();

        $campaign = $this->createCampaignHandler->handle(
            title: $request->input('title'),
            description: $request->input('description'),
            goalAmount: Money::fromString($request->input('goal_amount')),
            ownerId: $employee->id
        );

        return response()->json([
            'id' => $campaign->id,
            'title' => $campaign->title,
            'description' => $campaign->description,
            'goal_amount' => $campaign->goalAmount->toString(),
            'owner_id' => $campaign->ownerId,
            'status' => $campaign->status->value,
            'created_at' => $campaign->createdAt->format(DATE_ATOM),
        ], 201);
    }
}

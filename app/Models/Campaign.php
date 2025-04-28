<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CampaignFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 */
class Campaign extends Model
{
    /** @use HasFactory<CampaignFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'goal_amount_in_cents',
        'owner_id',
        'status',
    ];
}

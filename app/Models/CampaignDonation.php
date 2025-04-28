<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CampaignDonationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read \Database\Factories\CampaignFactory $factory
 */
class CampaignDonation extends Model
{
    /** @use HasFactory<CampaignDonationFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'campaign_id',
        'employee_id',
        'amount_in_cents',
        'created_at',
    ];
}

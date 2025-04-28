<?php

declare(strict_types=1);

namespace App\Domain\Campaign;

enum CampaignStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
}

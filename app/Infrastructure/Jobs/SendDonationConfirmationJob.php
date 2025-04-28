<?php

declare(strict_types=1);

namespace App\Infrastructure\Jobs;

use App\Domain\Donation\Donation;
use App\Mail\DonationConfirmationMail;
use App\Models\CampaignDonation;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class SendDonationConfirmationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly int $donationId
    ) {}

    public function handle(): void
    {
        $donation = CampaignDonation::find($this->donationId);

        if (! $donation) {
            Log::warning('Donation not found.', [
                'donation_id' => $this->donationId,
            ]);

            return;
        }

        $employee = Employee::find($donation->employee_id);

        if (! $employee) {
            Log::warning('Employee not found.', [
                'employee_id' => $donation->employee_id,
                'donation_id' => $donation->id,
            ]);

            return;
        }

        //        try {
        //            Log::info('About to send', [
        //                'donation_id' => $donation->id,
        //                'employee_id' => $employee->id,
        //            ]);
        //            Mail::to($employee->email)
        //                ->send(new DonationConfirmationMail($donation));
        //
        //            Log::info('Donation confirmation email sent');
        //        } catch (\Throwable $e) {
        //            Log::error('Failed', [
        //                'error' => $e->getMessage(),
        //            ]);
        //
        //            // retry
        //            throw $e;
        //        }
    }
}

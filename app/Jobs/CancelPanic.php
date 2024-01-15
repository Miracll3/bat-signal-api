<?php

namespace App\Jobs;

use App\Actions\WayneEnterpriseAPI\CancelPanicAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelPanic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $panicId;
    /**
     * Create a new job instance.
     */
    public function __construct($panicId)
    {
        $this->panicId = $panicId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = CancelPanicAction::handle($this->panicId);
        Log::info('CancelPanicAction Response: '. $response);
    }
}

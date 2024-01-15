<?php

namespace App\Jobs;

use App\Actions\WayneEnterpriseAPI\SendPanicAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPanic implements ShouldQueue
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
        $response = SendPanicAction::handle($this->panicId);
        Log::info('SendPanic Response: '. $response);
    }
}

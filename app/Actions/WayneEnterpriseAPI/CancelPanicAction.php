<?php

namespace App\Actions\WayneEnterpriseAPI;

use App\Models\ApiCallLog;
use App\Models\Panic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CancelPanicAction
{
    public static function handle($panicId)
    {
        $url = GetUrl::handle() . "panic/cancel";
        $token = GetToken::handle();

        $panic = Panic::findOrFail($panicId);

        $response = Http::withToken($token)->post($url, [
            'panic_id' => $panic->wayne_enterprise_id
        ]);

        // Log api call
        $logData = [
            'endpoint' => $url,
            'method' => 'POST',
            'body' => json_encode(['panic_id' => $panic->wayne_enterprise_id]),
            'status' => 'pending',
            'response' => null,
            'user_id' => $panic->created_by,
            'created_at' => now(),
        ];

        $logId = DB::table('api_call_logs')->insertGetId($logData);

        // Log the response
        DB::table('api_call_logs')
            ->where('id', $logId)
            ->update([
                'status' => $response->status(),
                'response' => $response,
                'updated_at' => now(),
            ]);

        Log::info('Wayne Enterprise API - Panic cancelled by user_id:'. Auth::user()->id . ' Panic :'. $panic->wayne_enterprise_id);
        
        $panic->delete();
        
        return $response;
    }

}

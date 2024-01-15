<?php

namespace App\Actions\WayneEnterpriseAPI;

use App\Models\Panic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendPanicAction
{
    public static function handle($panicId)
    {
        $url = GetUrl::handle() . "panic/create";
        $token = GetToken::handle();

        $panic = Panic::findOrFail($panicId);

        $requestData = [
            'longitude' => $panic->longitude,
            'latitude' => $panic->latitude,
            'panic_type' => $panic->panic_type ?? '',
            'details' => $panic->details ?? '',
            'reference_id' => $panic->id,
            'user_name' => Auth::user()->name
        ];

        $response = Http::withToken($token)->post($url, $requestData);

        // Log api call
        $logData = [
            'endpoint' => $url,
            'method' => 'POST',
            'body' => json_encode($requestData),
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

        $getResponse = collect($response->json());
        $responseData = $getResponse['data'];

        if($responseData['panic_id']){
            // Update the panic id in our database.
            $panic->wayne_enterprise_id = $responseData['panic_id'];
            $panic->save();
        }

        Log::info('Wayne Enterprise API - Panic created by user_id:'. Auth::user()->id . ' Panic :'. $responseData['panic_id']);
        
        return $response;
    }

}

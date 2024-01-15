<?php

namespace App\Http\Controllers\API;

use App\Actions\JsonResponseFormat;
use App\Actions\WayneEnterpriseAPI\SendPanicAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\PanicResource;
use App\Jobs\CancelPanic;
use App\Jobs\SendPanic;
use App\Models\Panic;
use App\Models\User;
use App\Notifications\APICallFailedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class PanicController extends Controller
{
    public function send(Request $request){

        $validator = Validator::make($request->all(),[
            'longitude' => 'required|string',
            'latitude' => 'required|string',
        ]);

        if ($validator->fails()) {
            return JsonResponseFormat::handle(data: $validator->errors() , message: 'Validation error', status: 400);
        }
        
        $panic = Panic::create([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'panic_type' => $request->panic_type ?? '',
            'details' => $request->details ?? '',
            'created_by' => Auth::user()->id
        ]);

        try {
            SendPanic::dispatch($panic->id);
            
            Log::info('User_ID: '. Auth::user()->id . ' CREATED Panic :'. $panic);

            return JsonResponseFormat::handle(data: ['panic_id' => $panic->id], message: 'Panic raised successfully', status: 200);

        } catch (\Exception $exception) {

            Log::error('Failed sending panic: '. $exception->getMessage());

            // notify admin
            $admin = User::where('email', 'admin@bat-signal-api.com')->first();
            Notification::send($admin , new APICallFailedNotification($exception->getMessage()));

            return JsonResponseFormat::handle(message: 'Failed sending panic, Admin has been notified.', status: 400);
        }

    }

    public function cancel(Request $request){

        $validator = Validator::make($request->all(),[
            'panic_id' => 'required|int'
        ]);

        if ($validator->fails()) {
            return JsonResponseFormat::handle(data: $validator->errors() , message: 'Validation error', status: 400);
        }

        $panic = Panic::find($request->panic_id);

        if($panic){

            try {
                CancelPanic::dispatch($panic->id);

                Log::info('User_ID: '. Auth::user()->id . ' CANCELLED Panic :'. $panic);
                
                return JsonResponseFormat::handle(message: 'Panic cancelled successfully', status: 200);

            } catch (\Exception $exception) {

                Log::error('Failed cancelling panic: '. $exception->getMessage());

                // notify admin
                $admin = User::where('email', 'admin@bat-signal-api.com')->first();
                Notification::send($admin , new APICallFailedNotification($exception->getMessage()));

                return JsonResponseFormat::handle(message: 'Failed cancelling panic, Admin has been notified.', status: 400);
            }

            $panic->delete();
        }
        else {
            return JsonResponseFormat::handle(message: "No panic found with given id.", status: 400);
        }
    }
    
    public function history(){
        $panicHistory = Panic::all();

        return JsonResponseFormat::handle(data: ['panics' => PanicResource::collection( $panicHistory)]);
    }
    
}

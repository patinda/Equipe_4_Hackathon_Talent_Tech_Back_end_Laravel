<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $client = Client::where('phone_number', $request->phone_number)->first();

        if (!$client) {
            return response()->json(['message' => 'failure'], 404);
        }

        try {
            $otp = rand(100000, 999999);
            $client->otp_code = $otp;
            $client->otp_expires_at = now()->addMinutes(10);
            $client->save();

            $twilio = new TwilioService();
            $twilio->sendSms($client->phone_number, "Votre code OTP Orange Money: {$otp}");

            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP via Twilio: ' . $e->getMessage());
            return response()->json(['message' => 'failure'], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $client = Client::where('phone_number', $request->phone_number)->first();

        if (!$client) {
            return response()->json(['message' => 'failure'], 404);
        }

        if ($client->otp_code !== $request->otp_code || $client->otp_expires_at < now()) {
            return response()->json(['message' => 'failure'], 400);
        }

        $client->otp_code = null;
        $client->otp_expires_at = null;
        $client->save();

        return response()->json(['message' => 'success']);
    }
}

<?php

namespace App\GraphQL\Mutations;

use App\Models\Client;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;


class OtpMutations
{
    public function sendOtp($root, array $args)
    {
        $client = Client::where('phone_number', $args['phone_number'])->first();

        if (!$client) {
            return "failure"; // Échec si le numéro n'existe pas
        }

        try {
            $otp = rand(100000, 999999);
            $client->otp_code = $otp;
            $client->otp_expires_at = now()->addMinutes(10);
            $client->save();

            $twilio = new TwilioService();
            $twilio->sendSms($client->phone_number, "Votre code OTP Orange Money: {$otp}");

            return "success"; // Succès si le SMS est envoyé
        } catch (\Exception $e) {
            // Log l'erreur pour diagnostic
            log::error('Failed to send OTP via Twilio: ' . $e->getMessage());
            return "failure"; // Échec si Twilio ne fonctionne pas
        }
    }


    // Méthode pour vérifier un OTP
    public function verifyOtp($root, array $args)
    {
        $client = Client::where('phone_number', $args['phone_number'])->first();

        if (!$client) {
            return "failure"; // Échec si le client n'est pas trouvé
        }

        if ($client->otp_code !== $args['otp_code'] || $client->otp_expires_at < now()) {
            return "failure"; // Échec si OTP invalide ou expiré
        }

        $client->otp_code = null;
        $client->otp_expires_at = null;
        $client->save();

        return "success"; // Succès
    }
}

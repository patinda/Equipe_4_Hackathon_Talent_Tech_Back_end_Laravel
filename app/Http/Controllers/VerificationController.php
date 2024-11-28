<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Incrémenter les tentatives et gérer le verrouillage spécifique et global.
     */
    private function incrementAttempts(Client $client, string $type)
    {
        $attemptsColumn = "{$type}_attempts";
        $lockColumn = "is_{$type}_locked";

        // Incrémenter les tentatives
        $client->increment($attemptsColumn);

        // Verrouiller si les tentatives atteignent 3
        if ($client->$attemptsColumn >= 3) {
            $client->$lockColumn = true;

            // Vérifier si toutes les fonctions sont bloquées et verrouiller globalement
            if (
                $client->phone_attempts >= 3 &&
                $client->cnib_attempts >= 3 &&
                $client->amount_attempts >= 3 &&
                $client->type_attempts >= 3
            ) {
                $client->is_global_locked = true;
                $client->locked_at = now();
            }
        }

        $client->save();
    }

    /**
     * Vérification du numéro de téléphone.
     */
    public function verifyPhoneNumber(Request $request)
    {
        $client = Client::where('phone_number', $request->phone_number)->first();

        return response()->json(['status' => $client ? 'success' : 'failure']);
    }
    /**
     * Vérification du CNIB.
     */
    public function verifyCnib(Request $request)
    {
        $client = Client::where('phone_number', $request->phone_number)->first();

        if (!$client) {
            return response()->json(['status' => 'failure'], 404);
        }

        if ($client->is_global_locked) {
            return response()->json(['status' => 'blocked'], 403);
        }

        if ($client->is_cnib_locked) {
            return response()->json(['status' => 'locked'], 403);
        }

        if (strtolower($client->cnib_number) !== strtolower($request->cnib_number)) {
            $this->incrementAttempts($client, 'cnib');
            return response()->json(['status' => 'failure']);
        }

        $client->update(['cnib_attempts' => 0, 'is_cnib_locked' => false]);
        return response()->json(['status' => 'success']);
    }

    /**
     * Vérification du montant de la transaction.
     */
    public function verifyTransaction(Request $request)
{
    $client = Client::where('phone_number', $request->phone_number)->first();

    if (!$client) {
        return response()->json(['status' => 'failure'], 404);
    }

    // Vérifier si le client est globalement verrouillé
    if ($client->is_global_locked) {
        return response()->json(['status' => 'blocked'], 403);
    }

    // Récupérer la dernière transaction
    $transaction = $client->transactions()->latest()->first();

    if (!$transaction) {
        return response()->json(['status' => 'failure'], 404);
    }

    $failedAttempts = 0;

    // Vérifier le montant
    if ((float)$transaction->transaction_amount !== (float)$request->amount) {
        $this->incrementAttempts($client, 'amount');
        $failedAttempts++;
    }

    // Vérifier le type de transaction
    if (
        !$transaction->typeTransaction ||
        strtolower($transaction->typeTransaction->name) !== strtolower($request->type)
    ) {
        $this->incrementAttempts($client, 'type');
        $failedAttempts++;
    }

    // Si le total des échecs atteint 3, verrouiller globalement
    if ($failedAttempts > 0) {
        $totalAttempts = $client->amount_attempts + $client->type_attempts;

        if ($totalAttempts >= 3) {
            $client->update([
                'is_global_locked' => true,
                'locked_at' => now(),
            ]);

            return response()->json(['status' => 'locked'], 403);
        }
    }

    // Réinitialiser les tentatives si tout est correct
    if ($failedAttempts === 0) {
        $client->update([
            'amount_attempts' => 0,
            'is_amount_locked' => false,
            'type_attempts' => 0,
            'is_type_locked' => false,
        ]);

        return response()->json(['status' => 'success']);
    }

    return response()->json(['status' => 'failure']);
}


}

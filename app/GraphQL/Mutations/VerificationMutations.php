<?php

namespace App\GraphQL\Mutations;

use App\Models\Client;

class VerificationMutations
{
    /**
     * Incrémente les tentatives et gère le verrouillage spécifique et global.
     */
    private function incrementAttempts(Client $client, string $type)
    {
        $attemptsColumn = "{$type}_attempts";
        $lockColumn = "is_{$type}_locked";

        // Incrémenter les tentatives
        $client->increment($attemptsColumn);

        // Verrouiller la vérification spécifique si nécessaire
        if ($client->$attemptsColumn >= 3) {
            $client->$lockColumn = true;

            // Vérifier si toutes les fonctions doivent entraîner un blocage global
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

    public function verifyPhoneNumber($root, array $args)
    {
        $client = Client::where('phone_number', $args['phone_number'])->first();

        return $client ? "success" : "failure";
    }

    /**
     * Vérification du CNIB.
     */
    public function verifyCnib($root, array $args)
    {
        $client = Client::where('phone_number', $args['phone_number'])->first();

        if (!$client) {
            return "failure"; // Client introuvable
        }

        // Vérifier si le client est globalement verrouillé
        if ($client->is_global_locked) {
            return "blocked";
        }

        // Vérifier si le client est verrouillé pour cette fonction
        if ($client->is_cnib_locked) {
            return "locked";
        }

        // Vérification du CNIB
        if (strtolower($client->cnib_number) !== strtolower($args['cnib_number'])) {
            $this->incrementAttempts($client, 'cnib');
            return "failure";
        }

        // Réinitialiser les tentatives en cas de succès
        $client->update(['cnib_attempts' => 0, 'is_cnib_locked' => false]);

        return "success";
    }

    /**
     * Vérification du montant de la transaction.
     */
    public function verifyTransactionAmount($root, array $args)
    {
        $client = Client::where('phone_number', $args['phone_number'])->first();

        if (!$client) {
            return "failure";
        }

        // Vérifier si le client est globalement verrouillé
        if ($client->is_global_locked) {
            return "blocked";
        }

        // Vérifier si le client est verrouillé pour cette fonction
        if ($client->is_amount_locked) {
            return "locked";
        }

        $transaction = $client->transactions()->orderBy('transaction_date', 'desc')->first();

        if (!$transaction || (float)$transaction->transaction_amount !== (float)$args['amount']) {
            $this->incrementAttempts($client, 'amount');
            return "failure";
        }

        // Réinitialiser les tentatives en cas de succès
        $client->update(['amount_attempts' => 0, 'is_amount_locked' => false]);

        return "success";
    }

    /**
     * Vérification du type de transaction.
     */
    public function verifyTransactionType($root, array $args)
    {
        $client = Client::where('phone_number', $args['phone_number'])->first();

        if (!$client) {
            return "failure";
        }

        // Vérifier si le client est globalement verrouillé
        if ($client->is_global_locked) {
            return "blocked";
        }

        // Vérifier si le client est verrouillé pour cette fonction
        if ($client->is_type_locked) {
            return "locked";
        }

        $transaction = $client->transactions()->orderBy('transaction_date', 'desc')->first();

        if (!$transaction || !$transaction->typeTransaction || strtolower($transaction->typeTransaction->name) !== strtolower($args['type'])) {
            $this->incrementAttempts($client, 'type');
            return "failure";
        }

        // Réinitialiser les tentatives en cas de succès
        $client->update(['type_attempts' => 0, 'is_type_locked' => false]);

        return "success";
    }
}

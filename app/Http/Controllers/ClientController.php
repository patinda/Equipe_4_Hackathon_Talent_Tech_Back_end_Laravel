<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all());
    }

    public function show($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('front_cnib_photo')) {
            $data['front_cnib_photo'] = $request->file('front_cnib_photo')->store('clients/front_cnib', 'public');
        }
        if ($request->hasFile('back_cnib_photo')) {
            $data['back_cnib_photo'] = $request->file('back_cnib_photo')->store('clients/back_cnib', 'public');
        }
        if ($request->hasFile('selfie_with_cnib')) {
            $data['selfie_with_cnib'] = $request->file('selfie_with_cnib')->store('clients/selfie', 'public');
        }

        $data['orange_money_password'] = Hash::make($data['orange_money_password']);

        $client = Client::create($data);

        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $data = $request->all();

        if ($request->hasFile('front_cnib_photo')) {
            Storage::delete($client->front_cnib_photo);
            $data['front_cnib_photo'] = $request->file('front_cnib_photo')->store('clients/front_cnib', 'public');
        }
        if ($request->hasFile('back_cnib_photo')) {
            Storage::delete($client->back_cnib_photo);
            $data['back_cnib_photo'] = $request->file('back_cnib_photo')->store('clients/back_cnib', 'public');
        }
        if ($request->hasFile('selfie_with_cnib')) {
            Storage::delete($client->selfie_with_cnib);
            $data['selfie_with_cnib'] = $request->file('selfie_with_cnib')->store('clients/selfie', 'public');
        }

        if (isset($data['orange_money_password'])) {
            $data['orange_money_password'] = Hash::make($data['orange_money_password']);
        }

        $client->update($data);

        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted']);
    }

    public function uploadSelfie(Request $request)
{
    // Valider la requête
    $request->validate([
        'phone_number' => 'required|string|exists:clients,phone_number',
        'selfie_with_cnib' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Fichier requis avec validation
    ]);

    // Trouver le client via le numéro de téléphone
    $client = Client::where('phone_number', $request->phone_number)->first();

    if (!$client) {
        return response()->json(['message' => 'Client not found'], 404);
    }

    // Supprimer l'ancien fichier si nécessaire
    if ($client->selfie_with_cnib) {
        \Storage::disk('public')->delete($client->selfie_with_cnib);
    }

    // Enregistrer le nouveau selfie
    $selfiePath = $request->file('selfie_with_cnib')->store('clients/selfie', 'public');

    // Mettre à jour le champ du client
    $client->update([
        'selfie_with_cnib' => $selfiePath,
    ]);

    return response()->json([
        'message' => 'Selfie uploaded successfully',
        'selfie_with_cnib' => $selfiePath,
    ], 200);
}

}

<?php

namespace App\GraphQL\Mutations;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientMutations
{
    public function createClientWithImages($root, array $args)
    {
        // Store images if provided
        $frontCnibPhoto = isset($args['front_cnib_photo'])
            ? $this->storeImage($args['front_cnib_photo'], 'clients/front_cnib')
            : null;

        $backCnibPhoto = isset($args['back_cnib_photo'])
            ? $this->storeImage($args['back_cnib_photo'], 'clients/back_cnib')
            : null;

        $selfieWithCnib = isset($args['selfie_with_cnib'])
            ? $this->storeImage($args['selfie_with_cnib'], 'clients/selfie')
            : null;

        // Create the client with hashed password
        return Client::create([
            'last_name' => $args['last_name'],
            'first_name' => $args['first_name'],
            'phone_number' => $args['phone_number'],
            'cnib_number' => $args['cnib_number'],
            'issue_date' => $args['issue_date'],
            'expiry_date' => $args['expiry_date'],
            'secondary_phone' => $args['secondary_phone'] ?? null,
            'birth_date' => $args['birth_date'],
            'birth_place' => $args['birth_place'],
            'issue_place' => $args['issue_place'],
            'front_cnib_photo' => $frontCnibPhoto,
            'back_cnib_photo' => $backCnibPhoto,
            'selfie_with_cnib' => $selfieWithCnib,
            'orange_money_password' => isset($args['orange_money_password'])
                ? Hash::make($args['orange_money_password'])
                : null,
        ]);
    }

    public function updateClientWithImages($root, array $args)
    {
        // Retrieve the client or throw a 404 if not found
        $client = Client::findOrFail($args['id']);

        // Update textual fields if provided
        $client->update(array_filter([
            'last_name' => $args['last_name'] ?? null,
            'first_name' => $args['first_name'] ?? null,
            'phone_number' => $args['phone_number'] ?? null,
            'cnib_number' => $args['cnib_number'] ?? null,
            'issue_date' => $args['issue_date'] ?? null,
            'expiry_date' => $args['expiry_date'] ?? null,
            'secondary_phone' => $args['secondary_phone'] ?? null,
            'birth_date' => $args['birth_date'] ?? null,
            'birth_place' => $args['birth_place'] ?? null,
            'issue_place' => $args['issue_place'] ?? null,
            'orange_money_password' => isset($args['orange_money_password'])
                ? Hash::make($args['orange_money_password'])
                : null,
        ]));

        // Update images if provided
        if (isset($args['front_cnib_photo'])) {
            $this->deleteImageIfExists($client->front_cnib_photo);
            $client->front_cnib_photo = $this->storeImage($args['front_cnib_photo'], 'clients/front_cnib');
        }

        if (isset($args['back_cnib_photo'])) {
            $this->deleteImageIfExists($client->back_cnib_photo);
            $client->back_cnib_photo = $this->storeImage($args['back_cnib_photo'], 'clients/back_cnib');
        }

        if (isset($args['selfie_with_cnib'])) {
            $this->deleteImageIfExists($client->selfie_with_cnib);
            $client->selfie_with_cnib = $this->storeImage($args['selfie_with_cnib'], 'clients/selfie');
        }

        $client->save();

        return $client;
    }

    private function storeImage($file, $directory)
    {
        return $file->store($directory, 'public');
    }

    private function deleteImageIfExists($path)
    {
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}

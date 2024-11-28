<?php

namespace App\GraphQL\Mutations;

use App\Models\Client;

class ResetMutations
{
    public function resetPassword($root, array $args)
    {
        $client = Client::where('phone_number', $args['phone_number'])->first();

        if (!$client) {
            return "failure";
        }

        $client->orange_money_password = bcrypt($args['new_password']);
        $client->save();

        return "success";
    }
}

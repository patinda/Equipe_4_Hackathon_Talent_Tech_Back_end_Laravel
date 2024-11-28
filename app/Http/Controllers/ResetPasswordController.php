<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $client = Client::where('phone_number', $request->phone_number)->first();

        if (!$client) {
            return response()->json(['message' => 'failure'], 404);
        }

        $client->orange_money_password = Hash::make($request->new_password);
        $client->save();

        return response()->json(['message' => 'success']);
    }
}

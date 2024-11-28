<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Client::all()->each(function ($client) {
            \App\Models\Transaction::factory(3)->create([
                'client_id' => $client->id,
                'transaction_type_id' => \App\Models\TypeTransaction::inRandomOrder()->first()->id,
            ]);
        });
    }
}

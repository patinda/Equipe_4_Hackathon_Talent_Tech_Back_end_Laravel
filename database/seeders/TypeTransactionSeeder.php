<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypeTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\TypeTransaction::factory()->create([
            'name' => 'Merchant Payment', // Updated to English
        ]);

        \App\Models\TypeTransaction::factory()->create([
            'name' => 'OM Withdrawal', // Updated to English
        ]);

        \App\Models\TypeTransaction::factory()->create([
            'name' => 'OM Deposit', // Updated to English
        ]);
    }
}

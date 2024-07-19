<?php

namespace Database\Seeders;

use App\Models\Commission;
use App\Models\CommissionRecipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommissionRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commissionRecipient = [["Hayat", 2], ["mihret", 1], ["feysel", 1], ["Alemu", 2], ["semira", 1]];

        foreach ($commissionRecipient as $recipient) {
            CommissionRecipient::create([
                'name' => $recipient[0],
                'branch_id' => $recipient[1],
            ]);
        }
    }
}

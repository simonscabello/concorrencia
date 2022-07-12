<?php

namespace Database\Seeders;

use App\Models\Conta;
use Illuminate\Database\Seeder;

class ContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conta::create([
            'numero' => '123456',
            'saldo' => 0,
        ]);

        Conta::create([
            'numero' => '654321',
            'saldo' => 0,
        ]);
    }
}

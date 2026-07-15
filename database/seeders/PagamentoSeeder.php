<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pagamentos = ['Dinheiro físico', 'TPA', 'Transferência'];
        
        foreach ($pagamentos as $pagamento) {
            \App\Models\Pagamento::firstOrCreate(['name' => $pagamento]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Pagamento;
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
            Pagamento::firstOrCreate(['name' => $pagamento]);
        }
    }
}

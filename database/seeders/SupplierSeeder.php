<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => '001',
                'supplier_nama' => 'Raja Grosir',
                'supplier_alamat' => 'Lowokwaru',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => '002',
                'supplier_nama' => 'Samsnug',
                'supplier_alamat' => 'Sengkaling',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => '003',
                'supplier_nama' => 'Sentra Fashion',
                'supplier_alamat' => 'Bandulan',
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}

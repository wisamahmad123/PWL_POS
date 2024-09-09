<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => '001',
                'barang_nama' => 'Aqua',
                'harga_beli' => '2500',
                'harga_jual' => '3000',
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => '002',
                'barang_nama' => 'LeMinerale',
                'harga_beli' => '2600',
                'harga_jual' => '3000',
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 1,
                'barang_kode' => '003',
                'barang_nama' => 'Club',
                'harga_beli' => '2400',
                'harga_jual' => '3000',
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => '004',
                'barang_nama' => 'Lays',
                'harga_beli' => '6000',
                'harga_jual' => '9000',
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 2,
                'barang_kode' => '005',
                'barang_nama' => 'Roti Tawar',
                'harga_beli' => '13000',
                'harga_jual' => '20000',
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 3,
                'barang_kode' => '006',
                'barang_nama' => 'TV',
                'harga_beli' => '1500000',
                'harga_jual' => '2000000',
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 3,
                'barang_kode' => '007',
                'barang_nama' => 'HP',
                'harga_beli' => '2000000',
                'harga_jual' => '2300000',
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 3,
                'barang_kode' => '008',
                'barang_nama' => 'Mouse',
                'harga_beli' => '150000',
                'harga_jual' => '200000',
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 3,
                'barang_kode' => '009',
                'barang_nama' => 'Monitor',
                'harga_beli' => '1700000',
                'harga_jual' => '2200000',
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 3,
                'barang_kode' => '010',
                'barang_nama' => 'SSD',
                'harga_beli' => '800000',
                'harga_jual' => '1500000',
            ],
            [
                'barang_id' => 11,
                'kategori_id' => 4,
                'barang_kode' => '011',
                'barang_nama' => 'Kemeja',
                'harga_beli' => '50000',
                'harga_jual' => '85000',
            ],
            [
                'barang_id' => 12,
                'kategori_id' => 4,
                'barang_kode' => '012',
                'barang_nama' => 'Kaos',
                'harga_beli' => '30000',
                'harga_jual' => '50000',
            ],
            [
                'barang_id' => 13,
                'kategori_id' => 5,
                'barang_kode' => '013',
                'barang_nama' => 'Trainer',
                'harga_beli' => '45000',
                'harga_jual' => '60000',
            ],
            [
                'barang_id' => 14,
                'kategori_id' => 5,
                'barang_kode' => '014',
                'barang_nama' => 'Jeans',
                'harga_beli' => '100000',
                'harga_jual' => '150000',
            ],
            [
                'barang_id' => 15,
                'kategori_id' => 5,
                'barang_kode' => '015',
                'barang_nama' => 'Cargo',
                'harga_beli' => '120000',
                'harga_jual' => '200000',
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}

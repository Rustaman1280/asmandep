<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Kelas;
use App\Models\Lab;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier1 = Supplier::first();
        $kelas = Kelas::where('nama', 'X RPL 1')->first();
        $lab = Lab::where('nama', 'Lab RPL')->first();

        if ($supplier1 && $lab) {
            Barang::firstOrCreate(
                ['kode_barang' => 'BRG001'],
                [
                    'nama_barang' => 'Laptop Asus',
                    'stock_barang' => 10,
                    'detail_barang' => 'Laptop untuk UNBK',
                    'supplier_id' => $supplier1->id,
                    'lokasi_id' => $lab->id,
                    'lokasi_type' => Lab::class,
                ]
            );
        }

        if ($supplier1 && $kelas) {
            Barang::firstOrCreate(
                ['kode_barang' => 'BRG002'],
                [
                    'nama_barang' => 'Proyektor Epson',
                    'stock_barang' => 2,
                    'detail_barang' => 'Proyektor kelas',
                    'supplier_id' => $supplier1->id,
                    'lokasi_id' => $kelas->id,
                    'lokasi_type' => Kelas::class,
                ]
            );
        }
    }
}

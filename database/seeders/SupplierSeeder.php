<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::firstOrCreate(
            ['nama_supplier' => 'CV. Maju Jaya'],
            [
                'alamat' => 'Jl. Sudirman No. 12',
                'no_telp' => '081234567890',
            ]
        );
        Supplier::firstOrCreate(
            ['nama_supplier' => 'PT. Teknologi Masa Depan'],
            [
                'alamat' => 'Jl. Thamrin No. 88',
                'no_telp' => '089876543210',
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Ruangan;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier1 = Supplier::first();
        $ruangKelas = Ruangan::where('nama', 'Ruang Kelas X RPL 1')->first();
        $ruangLab = Ruangan::where('nama', 'Lab RPL')->first()
            ?? Ruangan::where('jenis_ruangan', 'Ruang Laboratorium')->first();

        if ($supplier1 && $ruangLab) {
            $barang1 = Barang::firstOrCreate(
                ['kode_barang' => 'BRG001'],
                [
                    'nama_barang' => 'Laptop Asus',
                    'merk_model' => 'Asus VivoBook',
                    'no_seri_pabrik' => 'SN-LAP-001',
                    'ukuran' => '14 inch',
                    'bahan' => 'Plastik',
                    'tahun_pembuatan' => '2025',
                    'harga_perolehan' => 7500000,
                    'jumlah_baik' => 10,
                    'jumlah_rusak_ringan' => 0,
                    'jumlah_rusak_berat' => 0,
                    'keterangan_mutasi' => null,
                    'supplier_id' => $supplier1->id,
                ]
            );
            $barang1->ruangans()->syncWithoutDetaching([$ruangLab->id => ['jumlah' => 10]]);
        }

        if ($supplier1 && $ruangKelas) {
            $barang2 = Barang::firstOrCreate(
                ['kode_barang' => 'BRG002'],
                [
                    'nama_barang' => 'Proyektor Epson',
                    'merk_model' => 'Epson EB-X06',
                    'no_seri_pabrik' => 'SN-PRJ-002',
                    'ukuran' => '-',
                    'bahan' => 'Plastik',
                    'tahun_pembuatan' => '2024',
                    'harga_perolehan' => 6800000,
                    'jumlah_baik' => 2,
                    'jumlah_rusak_ringan' => 0,
                    'jumlah_rusak_berat' => 0,
                    'keterangan_mutasi' => 'Ditempatkan di ruang kelas',
                    'supplier_id' => $supplier1->id,
                ]
            );
            $barang2->ruangans()->syncWithoutDetaching([$ruangKelas->id => ['jumlah' => 2]]);
        }
    }
}

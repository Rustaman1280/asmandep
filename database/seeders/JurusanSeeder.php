<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::firstOrCreate(['kode' => 'RPL'], ['nama' => 'Rekayasa Perangkat Lunak']);
        Jurusan::firstOrCreate(['kode' => 'TKJ'], ['nama' => 'Teknik Komputer dan Jaringan']);
    }
}

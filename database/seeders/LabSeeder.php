<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lab;
use App\Models\Jurusan;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rpl = Jurusan::where('kode', 'RPL')->first();
        $tkj = Jurusan::where('kode', 'TKJ')->first();

        Lab::firstOrCreate(['jurusan_id' => $rpl->id, 'nama' => 'Lab RPL']);
        Lab::firstOrCreate(['jurusan_id' => $tkj->id, 'nama' => 'Lab TKJ']);
    }
}

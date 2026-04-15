<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing data: copy ruangan_id to pivot table
        $barangs = DB::table('barangs')->whereNotNull('ruangan_id')->get();
        foreach ($barangs as $barang) {
            $jumlah = ($barang->jumlah_baik ?? 0) + ($barang->jumlah_rusak_ringan ?? 0) + ($barang->jumlah_rusak_berat ?? 0);
            DB::table('barang_ruangan')->insertOrIgnore([
                'barang_id'  => $barang->id,
                'ruangan_id' => $barang->ruangan_id,
                'jumlah'     => $jumlah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
            $table->dropColumn('ruangan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->unsignedBigInteger('ruangan_id')->nullable()->after('supplier_id');
            $table->foreign('ruangan_id')->references('id')->on('ruangans')->onDelete('set null');
        });

        // Restore data from pivot: take the first ruangan for each barang
        $pivots = DB::table('barang_ruangan')
            ->select('barang_id', DB::raw('MIN(ruangan_id) as ruangan_id'))
            ->groupBy('barang_id')
            ->get();

        foreach ($pivots as $p) {
            DB::table('barangs')->where('id', $p->barang_id)->update(['ruangan_id' => $p->ruangan_id]);
        }
    }
};

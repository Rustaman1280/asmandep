<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'merk_model',
        'no_seri_pabrik',
        'ukuran',
        'bahan',
        'tahun_pembuatan',
        'harga_perolehan',
        'jumlah_baik',
        'jumlah_rusak_ringan',
        'jumlah_rusak_berat',
        'keterangan_mutasi',
        'supplier_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function ruangans()
    {
        return $this->belongsToMany(Ruangan::class, 'barang_ruangan')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }

    public function unitBarangs()
    {
        return $this->hasMany(UnitBarang::class);
    }

    public function getJumlahTotalAttribute()
    {
        return $this->jumlah_baik + $this->jumlah_rusak_ringan + $this->jumlah_rusak_berat;
    }
}

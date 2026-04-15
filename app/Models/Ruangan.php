<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $fillable = [
        'kategori',
        'jenis_ruangan',
        'nama',
        'tingkat',
        'jurusan_id',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_ruangan')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }
}

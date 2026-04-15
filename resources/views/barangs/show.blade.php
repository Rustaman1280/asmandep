@extends('layouts.admin')

@section('title', 'Detail Barang - ' . $barang->nama_barang)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center">
        <a href="{{ route('barangs.index') }}" class="p-2 text-slate-400 hover:text-slate-600 transition-colors mr-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h3 class="text-lg font-semibold text-slate-700">Detail Barang</h3>
    </div>

    {{-- Info Barang --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Barang</p>
                <p class="text-sm font-semibold text-slate-900">{{ $barang->nama_barang }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Merk/Model</p>
                <p class="text-sm text-slate-700">{{ $barang->merk_model ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">No. Seri Pabrik</p>
                <p class="text-sm font-mono text-slate-700">{{ $barang->no_seri_pabrik ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Ukuran</p>
                <p class="text-sm text-slate-700">{{ $barang->ukuran ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Bahan</p>
                <p class="text-sm text-slate-700">{{ $barang->bahan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tahun Pembuatan</p>
                <p class="text-sm text-slate-700">{{ $barang->tahun_pembuatan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nomor Kode</p>
                <p class="font-mono text-sm font-semibold text-slate-700">{{ $barang->kode_barang }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jumlah Total</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $barang->jumlah_total > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $barang->jumlah_total }} unit
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Harga Perolehan</p>
                <p class="text-sm font-semibold text-slate-700">
                    @if($barang->harga_perolehan)
                        Rp {{ number_format($barang->harga_perolehan, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Keadaan Barang</p>
                <div class="flex gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">B: {{ $barang->jumlah_baik }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">RR: {{ $barang->jumlah_rusak_ringan }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">RB: {{ $barang->jumlah_rusak_berat }}</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Lokasi</p>
                @if($barang->ruangans->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($barang->ruangans as $r)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $r->nama }}
                                <span class="text-blue-400 ml-1">({{ $r->jenis_ruangan }})</span>
                                <span class="ml-1.5 bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full text-[10px] font-bold">×{{ $r->pivot->jumlah }}</span>
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-700">-</p>
                @endif
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Supplier</p>
                <p class="text-sm text-slate-700">{{ $barang->supplier->nama_supplier ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Keterangan Mutasi</p>
                <p class="text-sm text-slate-700">{{ $barang->keterangan_mutasi ?? '-' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection

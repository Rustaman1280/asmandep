@extends('layouts.admin')

@section('title', 'Daftar Unit - ' . $barang->nama_barang)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ url()->previous() }}" class="p-2 text-slate-400 hover:text-slate-600 transition-colors mr-2 focus:outline-none focus:ring-2 focus:ring-slate-200 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Daftar Unit Barang</h3>
                <p class="text-sm text-slate-500">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(isset($filterRuanganId) && $filterRuanganId)
                <a href="{{ route('barangs.units', $barang) }}" class="inline-flex items-center px-4 py-2 border border-blue-200 rounded-xl shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Semua Unit
                </a>
            @endif
            <a href="{{ route('barangs.edit', $barang) }}" class="inline-flex items-center px-4 py-2 border border-slate-200 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                Edit Jumlah Utama
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h4 class="text-base font-semibold text-slate-800">Detail Fisik per Unit</h4>
                <p class="text-xs text-slate-500 mt-1">Total: {{ $barang->jumlah_total }} Unit (Baik: {{ $barang->jumlah_baik }}, Rusak Ringan: {{ $barang->jumlah_rusak_ringan }}, Rusak Berat: {{ $barang->jumlah_rusak_berat }})</p>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($barang->unitBarangs as $unit)
                <div x-data="{ isEditing: false }" class="bg-white border rounded-xl p-4 shadow-sm flex flex-col hover:shadow-md transition-shadow {{ $unit->kondisi == 'baik' ? 'border-emerald-200' : ($unit->kondisi == 'rusak_ringan' ? 'border-amber-200' : 'border-rose-200') }}">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-mono text-sm font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md">{{ $unit->kode_unit }}</span>
                        <span class="text-[10px] uppercase font-bold px-2.5 py-1 rounded-full {{ $unit->kondisi == 'baik' ? 'bg-emerald-100 text-emerald-800' : ($unit->kondisi == 'rusak_ringan' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                            {{ str_replace('_', ' ', $unit->kondisi) }}
                        </span>
                    </div>

                    {{-- Location Badge --}}
                    <div class="mb-3">
                        @if($unit->ruangan)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <svg class="w-3 h-3 mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $unit->ruangan->nama }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-50 text-slate-400 border border-slate-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Belum ditentukan
                            </span>
                        @endif
                    </div>

                    {{-- Text Display Mode --}}
                    <div x-show="!isEditing" class="flex flex-col flex-grow">
                        <p class="text-sm text-slate-600 mb-4 flex-grow">{{ $unit->keterangan ?? 'Tidak ada catatan tambahan.' }}</p>
                        <div class="pt-3 border-t border-slate-100 flex justify-end">
                            <button type="button" @click="isEditing = true" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Edit Unit</button>
                        </div>
                    </div>

                    {{-- Edit Form Mode --}}
                    <div x-show="isEditing" style="display: none;" class="flex flex-col flex-grow">
                        <form action="{{ route('unit-barangs.update', $unit) }}" method="POST" class="flex flex-col flex-grow">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi</label>
                                <select name="kondisi" class="w-full text-xs border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                                    <option value="baik" {{ $unit->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ $unit->kondisi == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ $unit->kondisi == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Lokasi</label>
                                <select name="ruangan_id" class="w-full text-xs border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                                    <option value="">-- Belum Ditentukan --</option>
                                    @foreach($ruangans as $ruangan)
                                        <option value="{{ $ruangan->id }}" {{ $unit->ruangan_id == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->nama }} ({{ $ruangan->jenis_ruangan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 flex-grow">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                                <textarea name="keterangan" rows="2" class="w-full text-xs border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50" placeholder="Catatan spesifik unit ini...">{{ $unit->keterangan }}</textarea>
                            </div>
                            <div class="pt-2 flex justify-end gap-2 mt-auto">
                                <button type="button" @click="isEditing = false" class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors">Batal</button>
                                <button type="submit" class="text-xs font-semibold px-3 py-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center">
                    <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">Belum ada rincian unit ter-generate.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('content')
<div class="max-w-4xl mx-auto" x-data="barangForm()">
    <div class="mb-6 flex items-center">
        <a href="{{ $redirectTo ?? route('barangs.index') }}" class="p-2 text-slate-400 hover:text-slate-600 transition-colors mr-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h3 class="text-lg font-semibold text-slate-700">Form Barang Baru</h3>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm p-8">
        <form action="{{ route('barangs.store') }}" method="POST">
            @csrf
            @if(!empty($redirectTo))
                <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
            @endif
            
            <div class="space-y-6">
                {{-- Baris 1: Nama & Merk --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_barang" class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: Laptop Asus" required>
                        @error('nama_barang') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="merk_model" class="block text-sm font-semibold text-slate-700 mb-2">Merk/Model</label>
                        <input type="text" name="merk_model" id="merk_model" value="{{ old('merk_model') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: Asus VivoBook 14">
                        @error('merk_model') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Baris 2: No Seri & Ukuran --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="no_seri_pabrik" class="block text-sm font-semibold text-slate-700 mb-2">No. Seri Pabrik</label>
                        <input type="text" name="no_seri_pabrik" id="no_seri_pabrik" value="{{ old('no_seri_pabrik') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: SN-12345678">
                        @error('no_seri_pabrik') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="ukuran" class="block text-sm font-semibold text-slate-700 mb-2">Ukuran</label>
                        <input type="text" name="ukuran" id="ukuran" value="{{ old('ukuran') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: 14 inch">
                        @error('ukuran') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Baris 3: Bahan & Tahun --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bahan" class="block text-sm font-semibold text-slate-700 mb-2">Bahan</label>
                        <input type="text" name="bahan" id="bahan" value="{{ old('bahan') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: Plastik, Metal">
                        @error('bahan') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tahun_pembuatan" class="block text-sm font-semibold text-slate-700 mb-2">Tahun Pembuatan/Pembelian</label>
                        <input type="text" name="tahun_pembuatan" id="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: 2026" maxlength="4">
                        @error('tahun_pembuatan') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Baris 4: Kode & Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_barang" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Kode Barang</label>
                        <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: BRG-001" required>
                        @error('kode_barang') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="harga_perolehan" class="block text-sm font-semibold text-slate-700 mb-2">Harga Beli/Perolehan (Rp)</label>
                        <input type="number" name="harga_perolehan" id="harga_perolehan" min="0" step="1" value="{{ old('harga_perolehan') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: 5000000">
                        @error('harga_perolehan') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Baris 5: Keadaan Barang (3 kolom) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Keadaan Barang (Jumlah)</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                            <label for="jumlah_baik" class="block text-xs font-bold text-emerald-700 mb-2">Baik</label>
                            <input type="number" name="jumlah_baik" id="jumlah_baik" min="0" value="{{ old('jumlah_baik', 0) }}" class="w-full px-4 py-3 bg-white border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 outline-none transition-all text-center text-lg font-bold text-emerald-700" required>
                            @error('jumlah_baik') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                            <label for="jumlah_rusak_ringan" class="block text-xs font-bold text-amber-700 mb-2">Rusak Ringan</label>
                            <input type="number" name="jumlah_rusak_ringan" id="jumlah_rusak_ringan" min="0" value="{{ old('jumlah_rusak_ringan', 0) }}" class="w-full px-4 py-3 bg-white border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-100 focus:border-amber-400 outline-none transition-all text-center text-lg font-bold text-amber-700" required>
                            @error('jumlah_rusak_ringan') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div class="bg-rose-50 rounded-xl p-4 border border-rose-100">
                            <label for="jumlah_rusak_berat" class="block text-xs font-bold text-rose-700 mb-2">Rusak Berat</label>
                            <input type="number" name="jumlah_rusak_berat" id="jumlah_rusak_berat" min="0" value="{{ old('jumlah_rusak_berat', 0) }}" class="w-full px-4 py-3 bg-white border border-rose-200 rounded-xl focus:ring-2 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all text-center text-lg font-bold text-rose-700" required>
                            @error('jumlah_rusak_berat') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Baris 6: Supplier --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="supplier_id" class="block text-sm font-semibold text-slate-700 mb-2">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama_supplier }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Baris 7: Lokasi Multi-Ruangan --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-semibold text-slate-700">Lokasi Penempatan</label>
                        <button type="button" @click="addLokasi()" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-semibold">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Lokasi
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <template x-for="(lok, index) in lokasiList" :key="index">
                            <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-200 group relative transition-all hover:border-blue-200">
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Ruangan</label>
                                    <select :name="'lokasi[' + index + '][ruangan_id]'" x-model="lok.ruangan_id" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all text-sm" required>
                                        <option value="">-- Pilih Ruangan --</option>
                                        @foreach($ruangans as $ruangan)
                                            <option value="{{ $ruangan->id }}">{{ $ruangan->nama }} ({{ $ruangan->jenis_ruangan }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32">
                                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jumlah</label>
                                    <input type="number" :name="'lokasi[' + index + '][jumlah]'" x-model="lok.jumlah" min="1" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all text-sm text-center font-semibold" required>
                                </div>
                                <div class="pt-6">
                                    <button type="button" @click="removeLokasi(index)" x-show="lokasiList.length > 1" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Hapus lokasi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div x-show="lokasiList.length === 0" class="text-center py-6 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 mt-3">
                        <p class="text-sm text-slate-400 italic">Belum ada lokasi. Klik "Tambah Lokasi" untuk menambahkan.</p>
                    </div>

                    @error('lokasi') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    @error('lokasi.*') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Baris 8: Keterangan Mutasi --}}
                <div>
                    <label for="keterangan_mutasi" class="block text-sm font-semibold text-slate-700 mb-2">Keterangan Mutasi</label>
                    <textarea name="keterangan_mutasi" id="keterangan_mutasi" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400" placeholder="Keterangan mutasi barang (opsional)">{{ old('keterangan_mutasi') }}</textarea>
                    @error('keterangan_mutasi') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                    <a href="{{ $redirectTo ?? route('barangs.index') }}" class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-all">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">Simpan Barang</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function barangForm() {
    // Pre-fill lokasi from query param or old values
    let initialLokasi = [];
    @if(old('lokasi'))
        initialLokasi = @json(old('lokasi'));
    @elseif(!empty($preLokasiId))
        initialLokasi = [{ ruangan_id: '{{ $preLokasiId }}', jumlah: 1 }];
    @endif

    if (initialLokasi.length === 0) {
        initialLokasi = [{ ruangan_id: '', jumlah: 1 }];
    }

    return {
        lokasiList: initialLokasi,
        addLokasi() {
            this.lokasiList.push({ ruangan_id: '', jumlah: 1 });
        },
        removeLokasi(index) {
            this.lokasiList.splice(index, 1);
        }
    }
}
</script>
@endsection

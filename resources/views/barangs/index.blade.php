@extends('layouts.admin')

@section('title', 'Data Barang')

@section('content')
<div x-data="barangTable()" x-cloak>
    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium flex items-center">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="p-6 border-b border-slate-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <h3 class="text-lg font-semibold text-slate-800">Daftar Barang</h3>
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Export --}}
                    <a href="{{ route('barangs.export') }}" class="inline-flex items-center px-3.5 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export
                    </a>
                    {{-- Import --}}
                    <button @click="showImport = true" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import
                    </button>
                    {{-- Column Toggle --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-3.5 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Kolom
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 z-50 p-3 max-h-80 overflow-y-auto">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 px-1">Tampilkan Kolom</p>
                            <template x-for="col in allColumns" :key="col.key">
                                <label class="flex items-center py-1.5 px-1 hover:bg-slate-50 rounded-lg cursor-pointer">
                                    <input type="checkbox" :checked="columns.includes(col.key)" @change="toggleColumn(col.key)" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 mr-2.5">
                                    <span class="text-sm text-slate-700" x-text="col.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                    {{-- Tambah --}}
                    <a href="{{ route('barangs.create') }}" class="inline-flex items-center px-3.5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah
                    </a>
                </div>
            </div>

            {{-- Search & Filter Bar --}}
            <div class="mt-4 flex flex-col md:flex-row gap-3">
                {{-- Search --}}
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" x-model="search" placeholder="Cari nama, kode, merk, supplier..."
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400">
                </div>
                {{-- Filter Supplier --}}
                <select x-model="filterSupplier" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all min-w-[180px]">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->nama_supplier }}">{{ $supplier->nama_supplier }}</option>
                    @endforeach
                </select>
                {{-- Filter Keadaan --}}
                <select x-model="filterKeadaan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all min-w-[160px]">
                    <option value="">Semua Keadaan</option>
                    <option value="baik">Ada Baik</option>
                    <option value="rusak_ringan">Ada Rusak Ringan</option>
                    <option value="rusak_berat">Ada Rusak Berat</option>
                </select>
                {{-- Reset --}}
                <button @click="search=''; filterSupplier=''; filterKeadaan=''" x-show="search || filterSupplier || filterKeadaan"
                        class="px-3 py-2.5 text-sm text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-4 text-center">No</th>
                        <th class="px-4 py-4" x-show="columns.includes('nama_barang')">Nama Barang</th>
                        <th class="px-4 py-4" x-show="columns.includes('merk_model')">Merk/Model</th>
                        <th class="px-4 py-4" x-show="columns.includes('no_seri_pabrik')">No. Seri Pabrik</th>
                        <th class="px-4 py-4" x-show="columns.includes('ukuran')">Ukuran</th>
                        <th class="px-4 py-4" x-show="columns.includes('bahan')">Bahan</th>
                        <th class="px-4 py-4" x-show="columns.includes('tahun')">Tahun</th>
                        <th class="px-4 py-4" x-show="columns.includes('kode_barang')">Kode</th>
                        <th class="px-4 py-4 text-center" x-show="columns.includes('jumlah')">Jumlah</th>
                        <th class="px-4 py-4 text-right" x-show="columns.includes('harga')">Harga</th>
                        <th class="px-3 py-4 text-center" x-show="columns.includes('keadaan')">
                            <span class="text-emerald-600">B</span> /
                            <span class="text-amber-600">RR</span> /
                            <span class="text-rose-600">RB</span>
                        </th>
                        <th class="px-4 py-4" x-show="columns.includes('supplier')">Supplier</th>
                        <th class="px-4 py-4" x-show="columns.includes('lokasi')">Lokasi</th>
                        <th class="px-4 py-4" x-show="columns.includes('mutasi')">Ket. Mutasi</th>
                        <th class="px-4 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($barangs as $barang)
                    <tr class="hover:bg-slate-50 transition-colors"
                        x-show="rowVisible('{{ addslashes($barang->nama_barang) }}', '{{ addslashes($barang->kode_barang) }}', '{{ addslashes($barang->merk_model) }}', '{{ addslashes($barang->supplier->nama_supplier ?? '') }}', {{ $barang->jumlah_baik }}, {{ $barang->jumlah_rusak_ringan }}, {{ $barang->jumlah_rusak_berat }})"
                        x-transition.opacity>
                        <td class="px-4 py-4 text-center text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-4 font-medium text-slate-900" x-show="columns.includes('nama_barang')">{{ $barang->nama_barang }}</td>
                        <td class="px-4 py-4" x-show="columns.includes('merk_model')">{{ $barang->merk_model ?? '-' }}</td>
                        <td class="px-4 py-4 font-mono text-xs" x-show="columns.includes('no_seri_pabrik')">{{ $barang->no_seri_pabrik ?? '-' }}</td>
                        <td class="px-4 py-4" x-show="columns.includes('ukuran')">{{ $barang->ukuran ?? '-' }}</td>
                        <td class="px-4 py-4" x-show="columns.includes('bahan')">{{ $barang->bahan ?? '-' }}</td>
                        <td class="px-4 py-4" x-show="columns.includes('tahun')">{{ $barang->tahun_pembuatan ?? '-' }}</td>
                        <td class="px-4 py-4 font-mono text-xs font-semibold text-slate-500" x-show="columns.includes('kode_barang')">{{ $barang->kode_barang }}</td>
                        <td class="px-4 py-4 text-center" x-show="columns.includes('jumlah')">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $barang->jumlah_total > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $barang->jumlah_total }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right whitespace-nowrap" x-show="columns.includes('harga')">
                            @if($barang->harga_perolehan) Rp {{ number_format($barang->harga_perolehan, 0, ',', '.') }} @else - @endif
                        </td>
                        <td class="px-3 py-4 text-center" x-show="columns.includes('keadaan')">
                            <div class="flex items-center justify-center gap-1">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700">{{ $barang->jumlah_baik }}</span>
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold bg-amber-50 text-amber-700">{{ $barang->jumlah_rusak_ringan }}</span>
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold bg-rose-50 text-rose-700">{{ $barang->jumlah_rusak_berat }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm" x-show="columns.includes('supplier')">{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm" x-show="columns.includes('lokasi')">
                            @if($barang->ruangans->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($barang->ruangans as $r)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $r->nama }}
                                            <span class="ml-1 text-blue-400">×{{ $r->pivot->jumlah }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else - @endif
                        </td>
                        <td class="px-4 py-4 max-w-[150px] truncate text-xs text-slate-500" x-show="columns.includes('mutasi')">{{ $barang->keterangan_mutasi ?? '-' }}</td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end space-x-1">
                                <a href="{{ route('barangs.units', $barang) }}" class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors text-xs font-medium">Unit</a>
                                <a href="{{ route('barangs.show', $barang) }}" class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">Detail</a>
                                <a href="{{ route('barangs.edit', $barang) }}" class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors text-xs font-medium">Edit</a>
                                <form action="{{ route('barangs.destroy', $barang) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-xs font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="px-6 py-8 text-center text-slate-400 italic">Belum ada data barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer info --}}
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex justify-between items-center">
            <span>Total: {{ $barangs->count() }} barang</span>
            <span>Klik <strong>Kolom</strong> untuk mengatur kolom yang ditampilkan</span>
        </div>
    </div>

    {{-- Import Modal --}}
    <div x-show="showImport" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div @click.away="showImport = false" x-transition class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">Import Data Barang</h3>
                <button @click="showImport = false" class="p-1 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('barangs.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div>
                    <p class="text-sm text-slate-600 mb-3">Upload file Excel (.xlsx, .xls, .csv) berisi data barang. Pastikan header kolom sesuai template.</p>
                    <a href="{{ route('barangs.template') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium mb-4">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Template Excel
                    </a>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih File</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl">
                    @error('file') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" @click="showImport = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function barangTable() {
    return {
        search: '',
        filterSupplier: '',
        filterKeadaan: '',
        showImport: false,
        allColumns: [
            { key: 'nama_barang', label: 'Nama Barang' },
            { key: 'merk_model', label: 'Merk/Model' },
            { key: 'no_seri_pabrik', label: 'No. Seri Pabrik' },
            { key: 'ukuran', label: 'Ukuran' },
            { key: 'bahan', label: 'Bahan' },
            { key: 'tahun', label: 'Tahun' },
            { key: 'kode_barang', label: 'Nomor Kode' },
            { key: 'jumlah', label: 'Jumlah' },
            { key: 'harga', label: 'Harga Perolehan' },
            { key: 'keadaan', label: 'Keadaan (B/RR/RB)' },
            { key: 'supplier', label: 'Supplier' },
            { key: 'lokasi', label: 'Lokasi' },
            { key: 'mutasi', label: 'Ket. Mutasi' },
        ],
        columns: JSON.parse(localStorage.getItem('barang_columns') || 'null') || [
            'nama_barang', 'merk_model', 'kode_barang', 'jumlah', 'harga', 'keadaan', 'supplier', 'lokasi', 'mutasi'
        ],
        toggleColumn(key) {
            if (this.columns.includes(key)) {
                this.columns = this.columns.filter(c => c !== key);
            } else {
                this.columns.push(key);
            }
            localStorage.setItem('barang_columns', JSON.stringify(this.columns));
        },
        rowVisible(nama, kode, merk, supplier, baik, rr, rb) {
            // Search filter
            if (this.search) {
                const q = this.search.toLowerCase();
                const haystack = (nama + ' ' + kode + ' ' + merk + ' ' + supplier).toLowerCase();
                if (!haystack.includes(q)) return false;
            }
            // Supplier filter
            if (this.filterSupplier && supplier !== this.filterSupplier) return false;
            // Keadaan filter
            if (this.filterKeadaan === 'baik' && baik <= 0) return false;
            if (this.filterKeadaan === 'rusak_ringan' && rr <= 0) return false;
            if (this.filterKeadaan === 'rusak_berat' && rb <= 0) return false;
            return true;
        }
    }
}
</script>
@endsection

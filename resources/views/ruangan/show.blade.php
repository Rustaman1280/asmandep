@extends('layouts.admin')

@section('title', 'Detail Ruangan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $ruangan->nama }}</h3>
                <p class="text-sm text-slate-500 mt-1">{{ $ruangan->jenis_ruangan }} • {{ $ruangan->kategori }}</p>
                @if($ruangan->jurusan)
                <p class="text-sm font-medium mt-3"><span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">Jurusan: {{ $ruangan->jurusan->nama }}</span></p>
                @endif
                @if($ruangan->tingkat)
                <p class="text-sm font-medium mt-1"><span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700">Tingkat: {{ $ruangan->tingkat }}</span></p>
                @endif
            </div>
            <div>
                <a href="{{ route('ruangans.index', ['jenis' => $ruangan->jenis_ruangan]) }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Kembali</a>
            </div>
        </div>
        
        <div class="p-6 bg-slate-50/50" x-data="ruanganBarangTable()" x-cloak>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4 pb-2 border-b border-slate-200">
                <h4 class="text-lg font-semibold text-slate-800">Daftar Barang di Ruangan Ini</h4>
                <div class="flex items-center gap-2">
                    {{-- Column Toggle --}}
                    <div class="relative items-center" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-3.5 py-2 bg-white text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors text-sm font-medium">
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
                    <a href="{{ route('barangs.create', ['lokasi_type' => 'ruangan', 'lokasi_id' => $ruangan->id, 'redirect_to' => route('ruangans.show', $ruangan)]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Barang
                    </a>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="mb-4 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" x-model="search" placeholder="Cari nama, kode, merk, supplier..."
                           class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder:text-slate-400">
                </div>
                <select x-model="filterKeadaan" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all min-w-[160px]">
                    <option value="">Semua Keadaan</option>
                    <option value="baik">Ada Baik</option>
                    <option value="rusak_ringan">Ada Rusak Ringan</option>
                    <option value="rusak_berat">Ada Rusak Berat</option>
                </select>
                <button @click="search=''; filterKeadaan=''" x-show="search || filterKeadaan"
                        class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded-xl transition-all flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </button>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
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
                                <th class="px-4 py-4 text-center" x-show="columns.includes('jumlah')">Jml Total</th>
                                <th class="px-4 py-4 text-center" x-show="columns.includes('jml_ruangan')">Di Ruangan Ini</th>
                                <th class="px-4 py-4 text-right" x-show="columns.includes('harga')">Harga</th>
                                <th class="px-3 py-4 text-center" x-show="columns.includes('keadaan')">
                                    <span class="text-emerald-600">B</span> /
                                    <span class="text-amber-600">RR</span> /
                                    <span class="text-rose-600">RB</span>
                                </th>
                                <th class="px-4 py-4" x-show="columns.includes('supplier')">Supplier</th>
                                <th class="px-4 py-4" x-show="columns.includes('mutasi')">Ket. Mutasi</th>
                                <th class="px-4 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($ruangan->barangs as $brg)
                                <tr class="hover:bg-slate-50 transition-colors"
                                    x-show="rowVisible('{{ addslashes($brg->nama_barang) }}', '{{ addslashes($brg->kode_barang) }}', '{{ addslashes($brg->merk_model) }}', '{{ addslashes($brg->supplier->nama_supplier ?? '') }}', {{ $brg->jumlah_baik }}, {{ $brg->jumlah_rusak_ringan }}, {{ $brg->jumlah_rusak_berat }})"
                                    x-transition.opacity>
                                    <td class="px-4 py-4 text-center text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 font-medium text-slate-900" x-show="columns.includes('nama_barang')">{{ $brg->nama_barang }}</td>
                                    <td class="px-4 py-4" x-show="columns.includes('merk_model')">{{ $brg->merk_model ?? '-' }}</td>
                                    <td class="px-4 py-4 font-mono text-xs" x-show="columns.includes('no_seri_pabrik')">{{ $brg->no_seri_pabrik ?? '-' }}</td>
                                    <td class="px-4 py-4" x-show="columns.includes('ukuran')">{{ $brg->ukuran ?? '-' }}</td>
                                    <td class="px-4 py-4" x-show="columns.includes('bahan')">{{ $brg->bahan ?? '-' }}</td>
                                    <td class="px-4 py-4" x-show="columns.includes('tahun')">{{ $brg->tahun_pembuatan ?? '-' }}</td>
                                    <td class="px-4 py-4 font-mono text-xs font-semibold text-slate-500" x-show="columns.includes('kode_barang')">{{ $brg->kode_barang }}</td>
                                    <td class="px-4 py-4 text-center" x-show="columns.includes('jumlah')">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brg->jumlah_total > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $brg->jumlah_total }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center" x-show="columns.includes('jml_ruangan')">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                            {{ $brg->pivot->jumlah }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right whitespace-nowrap" x-show="columns.includes('harga')">
                                        @if($brg->harga_perolehan) Rp {{ number_format($brg->harga_perolehan, 0, ',', '.') }} @else - @endif
                                    </td>
                                    <td class="px-3 py-4 text-center" x-show="columns.includes('keadaan')">
                                        <div class="flex items-center justify-center gap-1">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700" title="Baik">{{ $brg->jumlah_baik }}</span>
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold bg-amber-50 text-amber-700" title="Rusak Ringan">{{ $brg->jumlah_rusak_ringan }}</span>
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold bg-rose-50 text-rose-700" title="Rusak Berat">{{ $brg->jumlah_rusak_berat }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm" x-show="columns.includes('supplier')">{{ $brg->supplier->nama_supplier ?? '-' }}</td>
                                    <td class="px-4 py-4 max-w-[150px] truncate text-xs text-slate-500" x-show="columns.includes('mutasi')">{{ $brg->keterangan_mutasi ?? '-' }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end space-x-1">
                                            <a href="{{ route('barangs.units', ['barang' => $brg->id, 'ruangan_id' => $ruangan->id]) }}" class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors text-xs font-medium">Unit</a>
                                            <a href="{{ route('barangs.show', $brg) }}" class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">Detail</a>
                                            <a href="{{ route('barangs.edit', $brg) }}" class="inline-flex items-center px-2.5 py-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors text-xs font-medium">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($ruangan->barangs->isEmpty())
                            <tr>
                                <td colspan="15" class="px-4 py-8 text-center text-slate-400 italic">Belum ada barang di ruangan ini.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<script>
function ruanganBarangTable() {
    return {
        search: '',
        filterKeadaan: '',
        allColumns: [
            { key: 'nama_barang', label: 'Nama Barang' },
            { key: 'merk_model', label: 'Merk/Model' },
            { key: 'no_seri_pabrik', label: 'No. Seri Pabrik' },
            { key: 'ukuran', label: 'Ukuran' },
            { key: 'bahan', label: 'Bahan' },
            { key: 'tahun', label: 'Tahun' },
            { key: 'kode_barang', label: 'Nomor Kode' },
            { key: 'jumlah', label: 'Jumlah Total' },
            { key: 'jml_ruangan', label: 'Di Ruangan Ini' },
            { key: 'harga', label: 'Harga Perolehan' },
            { key: 'keadaan', label: 'Keadaan (B/RR/RB)' },
            { key: 'supplier', label: 'Supplier' },
            { key: 'mutasi', label: 'Ket. Mutasi' },
        ],
        columns: JSON.parse(localStorage.getItem('ruangan_barang_cols') || 'null') || [
            'nama_barang', 'merk_model', 'kode_barang', 'jumlah', 'jml_ruangan', 'harga', 'keadaan', 'supplier', 'mutasi'
        ],
        toggleColumn(key) {
            if (this.columns.includes(key)) {
                this.columns = this.columns.filter(c => c !== key);
            } else {
                this.columns.push(key);
            }
            localStorage.setItem('ruangan_barang_cols', JSON.stringify(this.columns));
        },
        rowVisible(nama, kode, merk, supplier, baik, rr, rb) {
            if (this.search) {
                const q = this.search.toLowerCase();
                const haystack = (nama + ' ' + kode + ' ' + merk + ' ' + supplier).toLowerCase();
                if (!haystack.includes(q)) return false;
            }
            if (this.filterKeadaan === 'baik' && baik <= 0) return false;
            if (this.filterKeadaan === 'rusak_ringan' && rr <= 0) return false;
            if (this.filterKeadaan === 'rusak_berat' && rb <= 0) return false;
            return true;
        }
    }
}
</script>
        </div>
    </div>
</div>
@endsection

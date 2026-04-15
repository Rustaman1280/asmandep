<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Ruangan;
use App\Exports\BarangExport;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with(['supplier', 'ruangans'])->get();
        $suppliers = Supplier::all();
        return view('barangs.index', compact('barangs', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $ruangans = Ruangan::all();
        $preLokasiId = request('lokasi_id');
        $redirectTo = request('redirect_to');
        return view('barangs.create', compact('suppliers', 'ruangans', 'preLokasiId', 'redirectTo'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang|max:255',
            'nama_barang' => 'required|string|max:255',
            'merk_model' => 'nullable|string|max:255',
            'no_seri_pabrik' => 'nullable|string|max:255',
            'ukuran' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tahun_pembuatan' => 'nullable|string|max:4',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'jumlah_baik' => 'required|integer|min:0',
            'jumlah_rusak_ringan' => 'required|integer|min:0',
            'jumlah_rusak_berat' => 'required|integer|min:0',
            'keterangan_mutasi' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'lokasi' => 'nullable|array',
            'lokasi.*.ruangan_id' => 'required|exists:ruangans,id',
            'lokasi.*.jumlah' => 'required|integer|min:1',
        ]);

        // Remove lokasi from validated data before creating barang
        $lokasiData = $validatedData['lokasi'] ?? [];
        unset($validatedData['lokasi']);

        $barang = Barang::create($validatedData);

        // Sync lokasi pivot
        $this->syncLokasi($barang, $lokasiData);

        $this->syncUnits($barang);

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Barang berhasil ditambahkan.');
        }

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['supplier', 'ruangans']);
        return view('barangs.show', compact('barang'));
    }

    public function units(Barang $barang)
    {
        $query = $barang->unitBarangs()->with('ruangan');
        
        if (request()->has('ruangan_id')) {
            $query->where('ruangan_id', request('ruangan_id'));
            $filterRuanganId = request('ruangan_id');
        } else {
            $filterRuanganId = null;
        }

        $unitBarangs = $query->get();
        $barang->setRelation('unitBarangs', $unitBarangs);
        
        $barang->load('ruangans');
        $ruangans = Ruangan::all();
        
        return view('barangs.units', compact('barang', 'ruangans', 'filterRuanganId'));
    }

    public function updateUnit(Request $request, \App\Models\UnitBarang $unitBarang)
    {
        $validated = $request->validate([
            'keterangan' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'ruangan_id' => 'nullable|exists:ruangans,id',
        ]);

        $oldKondisi = $unitBarang->kondisi;
        $newKondisi = $validated['kondisi'];

        $unitBarang->update($validated);

        if ($oldKondisi !== $newKondisi) {
            $barang = $unitBarang->barang;
            $oldCol = 'jumlah_' . $oldKondisi;
            $newCol = 'jumlah_' . $newKondisi;
            
            if ($barang->$oldCol > 0) {
                $barang->$oldCol -= 1;
            }
            $barang->$newCol += 1;
            $barang->save();
        }

        return back()->with('success', 'Rincian unit berhasil diperbarui.');
    }

    public function edit(Barang $barang)
    {
        $suppliers = Supplier::all();
        $ruangans = Ruangan::all();
        $barang->load('ruangans');
        return view('barangs.edit', compact('barang', 'suppliers', 'ruangans'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'kode_barang' => 'required|string|max:255|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'merk_model' => 'nullable|string|max:255',
            'no_seri_pabrik' => 'nullable|string|max:255',
            'ukuran' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tahun_pembuatan' => 'nullable|string|max:4',
            'harga_perolehan' => 'nullable|numeric|min:0',
            'jumlah_baik' => 'required|integer|min:0',
            'jumlah_rusak_ringan' => 'required|integer|min:0',
            'jumlah_rusak_berat' => 'required|integer|min:0',
            'keterangan_mutasi' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'lokasi' => 'nullable|array',
            'lokasi.*.ruangan_id' => 'required|exists:ruangans,id',
            'lokasi.*.jumlah' => 'required|integer|min:1',
        ]);

        // Remove lokasi from validated data before updating barang
        $lokasiData = $validatedData['lokasi'] ?? [];
        unset($validatedData['lokasi']);

        $barang->update($validatedData);

        // Sync lokasi pivot
        $this->syncLokasi($barang, $lokasiData);

        $this->syncUnits($barang);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Sync lokasi pivot table for a barang.
     */
    private function syncLokasi(Barang $barang, array $lokasiData)
    {
        $syncData = [];
        foreach ($lokasiData as $loc) {
            if (!empty($loc['ruangan_id'])) {
                // If same ruangan appears multiple times, sum the jumlah
                $rid = $loc['ruangan_id'];
                if (isset($syncData[$rid])) {
                    $syncData[$rid]['jumlah'] += (int) $loc['jumlah'];
                } else {
                    $syncData[$rid] = ['jumlah' => (int) $loc['jumlah']];
                }
            }
        }
        $barang->ruangans()->sync($syncData);
    }

    private function syncUnits(Barang $b)
    {
        // Simple append logic for now to make sure units reflect the capacity
        $kondisis = array_merge(
            array_fill(0, $b->jumlah_baik, 'baik'),
            array_fill(0, $b->jumlah_rusak_ringan, 'rusak_ringan'),
            array_fill(0, $b->jumlah_rusak_berat, 'rusak_berat')
        );
        $existingCount = $b->unitBarangs()->count();
        $targetCount = count($kondisis);
        
        if ($existingCount < $targetCount) {
            foreach ($kondisis as $i => $k) {
                if ($i < $existingCount) continue;
                $kodeUnit = $b->kode_barang . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
                \App\Models\UnitBarang::create([
                    'barang_id' => $b->id,
                    'kode_unit' => $kodeUnit,
                    'kondisi' => $k
                ]);
            }
        } elseif ($existingCount > $targetCount) {
             // For deletions, we truncate the overflow units.
             // Normally this requires more complex ID mapping.
             \App\Models\UnitBarang::where('barang_id', $b->id)->orderBy('id', 'desc')->take($existingCount - $targetCount)->delete();
        }

        // Auto-assign ruangan_id based on pivot amounts
        $b->load('ruangans');
        $units = $b->unitBarangs()->orderBy('id')->get();
        // Reset all to null first to re-distribute
        \App\Models\UnitBarang::where('barang_id', $b->id)->update(['ruangan_id' => null]);
        
        $unitIdx = 0;
        foreach ($b->ruangans as $ruangan) {
            $quota = $ruangan->pivot->jumlah;
            for ($i = 0; $i < $quota; $i++) {
                if (isset($units[$unitIdx])) {
                    $units[$unitIdx]->ruangan_id = $ruangan->id;
                    $units[$unitIdx]->save();
                    $unitIdx++;
                }
            }
        }
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new BarangExport, 'data-barang-' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new BarangImport, $request->file('file'));
            return redirect()->route('barangs.index')->with('success', 'Data barang berhasil diimpor dari Excel.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return redirect()->route('barangs.index')->with('error', 'Gagal impor: ' . implode('; ', array_slice($errors, 0, 5)));
        } catch (\Exception $e) {
            return redirect()->route('barangs.index')->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $export = new class implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\ShouldAutoSize {
            public function array(): array
            {
                return [
                    ['Laptop Asus', 'Asus Vivobook', 'SN-12345', '14 inch', 'Plastik', '2024', 'BRG-001', 5, 0, 0, 7500000, '', 'CV Maju Jaya'],
                ];
            }
            public function headings(): array
            {
                return ['Nama Barang', 'Merk/Model', 'No Seri Pabrik', 'Ukuran', 'Bahan', 'Tahun Pembuatan', 'Nomor Kode Barang', 'Jumlah Baik', 'Jumlah Rusak Ringan', 'Jumlah Rusak Berat', 'Harga Perolehan', 'Keterangan Mutasi', 'Supplier'];
            }
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                return [1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']]]];
            }
        };
        return Excel::download($export, 'template-import-barang.xlsx');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SekolahCrudController extends Controller
{
    // Menampilkan daftar master sekolah dan parameter kriteria (Tabel Manajemen)
    public function index()
    {
        $schools = Sekolah::with('nilaiAlternatif')->orderBy('id')->get();
        $criteriaList = Kriteria::orderBy('id')->get();

        // Ambil semua rating dan bangun array multi-dimensi: [sekolah_id][kriteria_id] = skor
        $nilaiRaw = NilaiAlternatif::all();
        $ratings = [];
        foreach ($nilaiRaw as $n) {
            $ratings[$n->sekolah_id][$n->kriteria_id] = (int)$n->skor_parameter;
        }

        return view('pages.admin.sekolah', compact('schools', 'criteriaList', 'ratings'));
    }

    // Menyimpan data sekolah baru beserta 6 skor kriteria penilaian awal
    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'scores' => 'required|array'
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat record sekolah baru
            $sekolah = Sekolah::create([
                'nama_sekolah' => $request->nama_sekolah
            ]);

            // 2. Masukkan 6 nilai alternatif kriteria
            foreach ($request->scores as $kriteriaId => $skor) {
                NilaiAlternatif::create([
                    'sekolah_id' => $sekolah->id,
                    'kriteria_id' => $kriteriaId,
                    'skor_parameter' => (float)$skor
                ]);
            }
        });

        return redirect()->back()->with('success', 'Sekolah baru berhasil ditambahkan ke database.');
    }

    // Memperbarui nama sekolah dan seluruh bobot kriteria parameternya
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'scores' => 'required|array'
        ]);

        DB::transaction(function () use ($request, $id) {
            // 1. Update nama sekolah
            $sekolah = Sekolah::findOrFail($id);
            $sekolah->update(['nama_sekolah' => $request->nama_sekolah]);

            // 2. Update atau buat baru skor kriteria jika belum ada (Upsert)
            foreach ($request->scores as $kriteriaId => $skor) {
                NilaiAlternatif::updateOrCreate(
                    ['sekolah_id' => $id, 'kriteria_id' => $kriteriaId],
                    ['skor_parameter' => (float)$skor]
                );
            }
        });

        return redirect()->back()->with('success', 'Data parameter sekolah berhasil diperbarui.');
    }

    // Menghapus instansi sekolah (Otomatis cascade menghapus nilai_alternatif)
    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $sekolah->delete();

        return redirect()->back()->with('success', 'Sekolah berhasil dihapus dari sistem.');
    }
}

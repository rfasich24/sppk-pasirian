<?php

namespace App\Http\Controllers;

use App\Services\AhpService;
use App\Models\Kriteria;
use App\Models\SppkUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhpController extends Controller
{
    protected $ahpService;

    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    // Menampilkan Halaman Validasi Visual Data (Pengganti check_data.php)
    public function halamanValidasi()
    {
        $kriteria = Kriteria::orderBy('id')->get();

        // Ambil data user beserta jumlah jawaban kuesionernya
        $users = SppkUser::select('users.*')
            ->selectRaw('COUNT(jawaban_kuesioner.id) as total_jawaban')
            ->leftJoin('jawaban_kuesioner', 'users.id', '=', 'jawaban_kuesioner.user_id')
            ->groupBy('users.id')
            ->orderBy('users.id')
            ->get();

        // Query pivot untuk menampilkan nilai alternatif sekolah
        $sekolahData = DB::select("
            SELECT s.nama_sekolah,
                MAX(CASE WHEN k.nama_kriteria = 'Jarak' THEN n.skor_parameter END) as jarak,
                MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN n.skor_parameter END) as fasilitas,
                MAX(CASE WHEN k.nama_kriteria = 'Biaya' THEN n.skor_parameter END) as biaya,
                MAX(CASE WHEN k.nama_kriteria = 'Akreditasi' THEN n.skor_parameter END) as akreditasi,
                MAX(CASE WHEN k.nama_kriteria = 'Prestasi' THEN n.skor_parameter END) as prestasi,
                MAX(CASE WHEN k.nama_kriteria = 'Ekstrakurikuler' THEN n.skor_parameter END) as ekstrakurikuler
            FROM public.sekolah s
            LEFT JOIN public.nilai_alternatif n ON s.id = n.sekolah_id
            LEFT JOIN public.kriteria k ON n.kriteria_id = k.id
            GROUP BY s.id, s.nama_sekolah
            ORDER BY s.id
        ");

        return view('pages.check-data', compact('kriteria', 'users', 'sekolahData'));
    }

    // Menampilkan Halaman Perhitungan & Matriks Saaty Responden (Pengganti matrix_viewer.php & ahp_processor.php)
    public function halamanMatriks()
    {
        // Jalankan service core AHP
        $analisisAhp = $this->ahpService->hitungAhp();

        // Ambil data spesifik untuk rekonstruksi matriks per individu responden
        $users = SppkUser::orderBy('id')->get();
        $jawabanRaw = DB::table('jawaban_kuesioner')->get();

        $rawMatrix = [];
        foreach ($jawabanRaw as $j) {
            $rawMatrix[$j->user_id][$j->kriteria_id_1][$j->kriteria_id_2] = (float)$j->nilai_saaty;
        }

        return view('pages.matrix-viewer', array_merge($analisisAhp, [
            'users' => $users,
            'rawMatrix' => $rawMatrix
        ]));
    }

    public function simpanBobotGlobal(Request $request)
    {
        $hasilAhp = $this->ahpService->hitungAhp();

        if ($hasilAhp['is_konsisten']) {
            foreach ($hasilAhp['bobot_kriteria'] as $id_kriteria => $nilai_bobot) {
                Kriteria::where('id', $id_kriteria)->update(['bobot_global' => $nilai_bobot]);
            }
            return redirect()->back()->with('success', 'Sore! Bobot kriteria berhasil disimpan ke database.');
        }

        return redirect()->back()->with('error', 'Matriks tidak konsisten, gagal menyimpan bobot.');
    }
}

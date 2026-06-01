<?php

namespace App\Http\Controllers;

use App\Services\AhpService;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class AhpController extends Controller
{
    protected $ahpService;

    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    // Fungsi dump data untuk validasi angka presisi matematika
    public function cekHitunganDua()
    {
        $hasilAhp = $this->ahpService->hitungAhp();
        return response()->json([
            'status' => 'success',
            'lambda_max' => round($hasilAhp['lambda_max'], 4),
            'consistency_index_ci' => round($hasilAhp['ci'], 4),
            'consistency_ratio_cr' => round($hasilAhp['cr'], 4),
            'is_konsisten' => $hasilAhp['is_konsisten'],
            'bobot_kriteria_eigenvector' => array_map(fn($v) => round($v, 6), $hasilAhp['bobot_kriteria'])
        ]);
    }

    // Fungsi simpan bobot otomatis ke database
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

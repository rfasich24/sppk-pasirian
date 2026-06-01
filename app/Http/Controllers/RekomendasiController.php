<?php

namespace App\Http\Controllers;

use App\Services\SmartService;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    protected $smartService;

    public function __construct(SmartService $smartService)
    {
        $this->smartService = $smartService;
    }

    // Menampilkan halaman pencarian rekomendasi (Pengganti rekomendasi.php)
    public function halamanRekomendasi(Request $request)
    {
        $kriteriaAll = Kriteria::whereNotNull('bobot_global')->orderBy('id')->get();

        $hasilRekomendasi = [];
        $kriteriaTerpilihIds = $request->input('kriteria_dipilih', []);
        $limit = (int) $request->input('limit', 5);

        if ($request->isMethod('post') && !empty($kriteriaTerpilihIds)) {
            $hasilRekomendasi = $this->smartService->hitungSmart($kriteriaTerpilihIds, $limit);
        }

        return view('pages.rekomendasi', compact('kriteriaAll', 'hasilRekomendasi', 'kriteriaTerpilihIds', 'limit'));
    }
}

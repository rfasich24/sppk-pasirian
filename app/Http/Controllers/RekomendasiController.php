<?php

namespace App\Http\Controllers\Admin; // Sesuaikan namespace jika ada di folder admin atau general

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

    public function halamanRekomendasi(Request $request)
    {
        $kriteriaAll = Kriteria::orderBy('id')->get();
        $kriteriaTerpilihIds = $request->input('kriteria_dipilih');
        $userPreferences = $request->input('user_preferences', []); // <-- AMBIL INPUT PREFERENSI JAWABAN

        if ($request->isMethod('get') && empty($kriteriaTerpilihIds)) {
            $kriteriaTerpilihIds = Kriteria::orderBy('id')->take(4)->pluck('id')->toArray();
        } elseif (empty($kriteriaTerpilihIds)) {
            $kriteriaTerpilihIds = [];
        }

        // Set default pilihan jawaban awal untuk kunjungan pertama
        if ($request->isMethod('get')) {
            foreach ($kriteriaTerpilihIds as $id) {
                $userPreferences[$id] = 4; // Default memilih opsi ber-skor 4
            }
        }

        $limit = $request->input('limit', 'all');
        $recommData = null;

        if (!empty($kriteriaTerpilihIds)) {
            // Kirimkan array preferensi ke service layer
            $recommData = $this->smartService->hitungSmart($kriteriaTerpilihIds, $limit, $userPreferences);
        }

        return view('pages.rekomendasi', compact('kriteriaAll', 'recommData', 'kriteriaTerpilihIds', 'limit', 'userPreferences'));
    }
}

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

    public function halamanRekomendasi(Request $request)
    {
        $kriteriaAll = Kriteria::orderBy('id')->get();
        $kriteriaTerpilihIds = $request->input('kriteria_dipilih');

        // Simulasi awal React: otomatis mencentang 4 kriteria pertama di kunjungan awal
        if ($request->isMethod('get') && empty($kriteriaTerpilihIds)) {
            $kriteriaTerpilihIds = Kriteria::orderBy('id')->take(4)->pluck('id')->toArray();
        } elseif (empty($kriteriaTerpilihIds)) {
            $kriteriaTerpilihIds = [];
        }

        $limit = $request->input('limit', 'all');
        $recommData = null;

        if (!empty($kriteriaTerpilihIds)) {
            $recommData = $this->smartService->hitungSmart($kriteriaTerpilihIds, $limit);
        }

        return view('pages.rekomendasi', compact('kriteriaAll', 'recommData', 'kriteriaTerpilihIds', 'limit'));
    }
}

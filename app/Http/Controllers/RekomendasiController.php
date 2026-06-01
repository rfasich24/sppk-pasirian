<?php

namespace App\Http\Controllers;

use App\Services\SmartService;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    protected $smartService;

    public function __construct(SmartService $smartService)
    {
        $this->smartService = $smartService;
    }

    // Fungsi uji coba simulasi kalkulasi SMART via JSON
    public function testSmartEksekusi()
    {
        // Simulasi jika user mencentang semua ID kriteria (1 sampai 6)
        $kriteriaSimulasi = [1, 2, 3, 4, 5, 6];
        $limit = 5;

        $hasil = $this->smartService->hitungSmart($kriteriaSimulasi, $limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Simulasi Perankingan SMART (Kriteria 1-6)',
            'data' => $hasil
        ]);
    }
}

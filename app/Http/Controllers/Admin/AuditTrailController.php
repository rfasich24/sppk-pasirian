<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AhpService;
use App\Services\SmartService;
use App\Models\Kriteria;
use App\Models\Sekolah;
use App\Models\SppkUser;
use App\Models\JawabanKuesioner;
use App\Models\NilaiAlternatif;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    protected $ahpService;
    protected $smartService;

    public function __construct(AhpService $ahpService, SmartService $smartService)
    {
        $this->ahpService = $ahpService;
        $this->smartService = $smartService;
    }

    // Panel Pengawasan Rumus Matematika AHP (Lengkap dengan Inspeksi Individu)
    public function ahpAudit(Request $request)
    {
        // 1. Ambil hasil hitungan core AHP gabungan
        $analisisAhp = $this->ahpService->hitungAhp();

        // 2. Ambil data semua pakar untuk dropdown
        $users = SppkUser::orderBy('id')->get();
        $selectedUserId = $request->input('user_id', $users->first()?->id);

        // 3. Rekonstruksi Matriks Saaty Individu Pakar Terpilih
        $kriteria = $analisisAhp['kriteria'];
        $individualMatrix = [];

        // Ambil baris jawaban mentah khusus pakar ini di database
        $userAnswers = JawabanKuesioner::where('user_id', $selectedUserId)->get();
        $rawAnswers = [];
        foreach ($userAnswers as $ans) {
            $rawAnswers[$ans->kriteria_id_1][$ans->kriteria_id_2] = (float)$ans->nilai_saaty;
        }

        // Petakan ke dalam susunan matriks persegi 6x6 lengkap dengan hukum invers komparasi
        foreach ($kriteria as $row) {
            foreach ($kriteria as $col) {
                $r = $row->id;
                $c = $col->id;

                if ($r == $c) {
                    $individualMatrix[$r][$c] = 1.0;
                } elseif (isset($rawAnswers[$r][$c])) {
                    $individualMatrix[$r][$c] = $rawAnswers[$r][$c];
                } elseif (isset($rawAnswers[$c][$r])) {
                    $individualMatrix[$r][$c] = 1 / $rawAnswers[$c][$r];
                } else {
                    $individualMatrix[$r][$c] = 1.0;
                }
            }
        }

        return view('pages.admin.ahp_audit', array_merge($analisisAhp, [
            'users' => $users,
            'selectedUserId' => $selectedUserId,
            'individualMatrix' => $individualMatrix
        ]));
    }

    public function smartAudit()
    {
        $kriteriaList = Kriteria::orderBy('id')->get();
        $schools = Sekolah::orderBy('id')->get();

        $nilaiRaw = NilaiAlternatif::all();
        $ratings = [];
        foreach ($nilaiRaw as $n) {
            $ratings[$n->sekolah_id][$n->kriteria_id] = (float)$n->skor_parameter;
        }

        $kriteriaAllIds = $kriteriaList->pluck('id')->toArray();
        $smartData = $this->smartService->hitungSmart($kriteriaAllIds, 'all');

        return view('pages.admin.smart_audit', compact('kriteriaList', 'schools', 'ratings', 'smartData'));
    }
    // Fungsi mengubah tipe kriteria secara instan
    public function toggleType($id)
    {
        $kriteria = Kriteria::findOrFail($id);

        // Logika sakelar: jika benefit ubah ke cost, jika cost ubah ke benefit
        $kriteria->tipe = ($kriteria->tipe === 'benefit') ? 'cost' : 'benefit';
        $kriteria->save();

        return redirect()->back()->with('success', "Tipe kriteria {$kriteria->kode_kriteria} ({$kriteria->nama_kriteria}) berhasil diubah menjadi " . strtoupper($kriteria->tipe) . ".");
    }
}

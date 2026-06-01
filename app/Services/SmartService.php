<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Sekolah;
use App\Models\NilaiAlternatif;

class SmartService
{
    public function hitungSmart(array $kriteriaTerpilihIds, string $limit = 'all'): array
    {
        // 1. Ambil semua kriteria untuk perhitungan parameter lengkap
        $kriteriaAll = Kriteria::orderBy('id')->get();
        $kriteriaFilter = Kriteria::whereIn('id', $kriteriaTerpilihIds)->whereNotNull('bobot_global')->get();

        $totalBobotAsal = $kriteriaFilter->sum('bobot_global');

        // Hitung Re-Normalisasi Bobot SMART real-time
        $bobotBaru = [];
        $normalizedWeights = [];
        foreach ($kriteriaFilter as $k) {
            $weight = $totalBobotAsal > 0 ? (float)$k->bobot_global / $totalBobotAsal : 0;
            $bobotBaru[$k->id] = $weight;
            $normalizedWeights[$k->kode_kriteria] = $weight;
        }

        // Ambil semua nilai alternatif untuk pemetaan skor Max dan Min kriteria
        $nilaiRaw = NilaiAlternatif::all();
        $skorPerKriteria = [];
        $nilaiSekolahMatrix = [];

        foreach ($nilaiRaw as $n) {
            $skorPerKriteria[$n->kriteria_id][] = (float)$n->skor_parameter;
            $nilaiSekolahMatrix[$n->sekolah_id][$n->kriteria_id] = (float)$n->skor_parameter;
        }

        $cMax = []; $cMin = [];
        foreach ($skorPerKriteria as $kritId => $kumpulanSkor) {
            $cMax[$kritId] = max($kumpulanSkor);
            $cMin[$kritId] = min($kumpulanSkor);
        }

        $sekolahAll = Sekolah::all();
        $results = [];

        // Hitung nilai utilitas dan skor gabungan per sekolah
        foreach ($sekolahAll as $s) {
            $totalSkorSmart = 0;
            $utilities = [];
            $rawScores = [];

            foreach ($kriteriaAll as $k) {
                $skorAsli = $nilaiSekolahMatrix[$s->id][$k->id] ?? 0;
                $rawScores[$k->kode_kriteria] = $skorAsli;

                $max = $cMax[$k->id] ?? 0;
                $min = $cMin[$k->id] ?? 0;

                if ($max == $min) {
                    $utilitas = 1.0;
                } else {
                    if ($k->tipe === 'benefit') {
                        $utilitas = ($skorAsli - $min) / ($max - $min);
                    } else {
                        $utilitas = ($max - $skorAsli) / ($max - $min);
                    }
                }
                $utilities[$k->kode_kriteria] = $utilitas;

                // Akumulasi skor jika kriteria ini dipilih oleh user
                if (in_array($k->id, $kriteriaTerpilihIds)) {
                    $totalSkorSmart += $utilitas * $bobotBaru[$k->id];
                }
            }

            $results[] = [
                'schoolId' => $s->id,
                'schoolName' => $s->nama_sekolah,
                'finalScore' => $totalSkorSmart * 100, // Diubah ke persen sesuai visual React
                'rawScores' => $rawScores,
                'utilities' => $utilities
            ];
        }

        // Sorting dari nilai tertinggi
        usort($results, function ($a, $b) {
            return $b['finalScore'] <=> $a['finalScore'];
        });

        // Sematkan nomor ranking
        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;
        }

        // Potong data jika ada limitasi
        if ($limit !== 'all') {
            $results = array_slice($results, 0, (int)$limit);
        }

        return [
            'normalizedWeights' => $normalizedWeights,
            'results' => $results
        ];
    }
}

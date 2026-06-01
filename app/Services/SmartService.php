<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Sekolah;
use App\Models\NilaiAlternatif;

class SmartService
{
    public function hitungSmart(array $kriteriaTerpilihIds, string $limit = 'all', array $userPreferences = []): array
    {
        // 1. Ambil seluruh data master kriteria
        $kriteriaAll = Kriteria::orderBy('id')->get();
        $kriteriaFilter = Kriteria::whereIn('id', $kriteriaTerpilihIds)->whereNotNull('bobot_global')->get();

        $totalBobotAsal = $kriteriaFilter->sum('bobot_global');

        // Re-Normalisasi Bobot SMART secara real-time
        $bobotBaru = [];
        $normalizedWeights = [];
        foreach ($kriteriaFilter as $k) {
            $weight = $totalBobotAsal > 0 ? (float)$k->bobot_global / $totalBobotAsal : 0;
            $bobotBaru[$k->id] = $weight;
            $normalizedWeights[$k->kode_kriteria] = $weight;
        }

        // 2. Ambil nilai alternatif sekolah untuk dipetakan ke dalam matriks
        $nilaiRaw = NilaiAlternatif::all();
        $nilaiSekolahMatrix = [];
        $skorPerKriteria = [];

        foreach ($nilaiRaw as $n) {
            $nilaiSekolahMatrix[$n->sekolah_id][$n->kriteria_id] = (float)$n->skor_parameter;
            $skorPerKriteria[$n->kriteria_id][] = (float)$n->skor_parameter;
        }

        // Cari batas nilai Max & Min global di database untuk fallback standard
        $cMax = []; $cMin = [];
        foreach ($skorPerKriteria as $kritId => $kumpulanSkor) {
            $cMax[$kritId] = max($kumpulanSkor);
            $cMin[$kritId] = min($kumpulanSkor);
        }

        $sekolahAll = Sekolah::all();
        $results = [];

        // 3. Hitung kedekatan preferensi (Proximity Matching)
        foreach ($sekolahAll as $s) {
            $totalSkorSmart = 0;
            $utilities = [];
            $rawScores = [];

            foreach ($kriteriaAll as $k) {
                $skorAsli = $nilaiSekolahMatrix[$s->id][$k->id] ?? 0;
                $rawScores[$k->kode_kriteria] = $skorAsli;

                // Cek apakah user menentukan preferensi jawaban untuk kriteria ini
                if (in_array($k->id, $kriteriaTerpilihIds) && isset($userPreferences[$k->id])) {
                    $prefUser = (float)$userPreferences[$k->id];

                    // Hitung jarak selisih absolut antara nilai sekolah dengan kemauan user
                    $distance = abs($skorAsli - $prefUser);

                    // Cari jarak selisih maksimum yang mungkin terjadi sebagai pembagi normalisasi
                    $max = $cMax[$k->id] ?? 5;
                    $min = $cMin[$k->id] ?? 1;
                    $maxDistance = max(abs($max - $prefUser), abs($min - $prefUser));

                    if ($maxDistance == 0) {
                        $utilitas = 1.0;
                    } else {
                        // Semakin kecil jarak selisih, nilai utilitasnya semakin mendekati 1.0 (Sempurna)
                        $utilitas = 1.0 - ($distance / $maxDistance);
                    }
                } else {
                    // FALLBACK STANDARD: Jika user tidak input target pilihan, gunakan rumus SMART standard kemarin
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
                }

                $utilities[$k->kode_kriteria] = $utilitas;

                // Akumulasikan ke skor akhir jika kriteria dicentang
                if (in_array($k->id, $kriteriaTerpilihIds)) {
                    $totalSkorSmart += $utilitas * $bobotBaru[$k->id];
                }
            }

            $results[] = [
                'schoolId' => $s->id,
                'schoolName' => $s->nama_sekolah,
                'finalScore' => $totalSkorSmart * 100, // Presentasi persen
                'rawScores' => $rawScores,
                'utilities' => $utilities
            ];
        }

        // Urutkan sekolah dari skor tertinggi ke rendah
        usort($results, function ($a, $b) {
            return $b['finalScore'] <=> $a['finalScore'];
        });

        foreach ($results as $index => &$res) {
            $res['rank'] = $index + 1;
        }

        if ($limit !== 'all') {
            $results = array_slice($results, 0, (int)$limit);
        }

        return [
            'normalizedWeights' => $normalizedWeights,
            'results' => $results
        ];
    }
}

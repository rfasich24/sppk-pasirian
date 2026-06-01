<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Sekolah;
use App\Models\NilaiAlternatif;

class SmartService
{
    public function hitungSmart(array $kriteriaTerpilihIds, int $limit = 5): array
    {
        if (empty($kriteriaTerpilihIds)) {
            return [];
        }

        // 1. FILTER SMART: Ambil data kriteria yang dicentang oleh user saja
        $kriteriaFilter = Kriteria::whereIn('id', $kriteriaTerpilihIds)
            ->whereNotNull('bobot_global')
            ->get();

        $totalBobotAsal = $kriteriaFilter->sum('bobot_global');

        if ($totalBobotAsal == 0) {
            return [];
        }

        // LANGKAH 1 SMART: Re-Normalisasi Bobot agar total bobot kriteria terpilih = 1.0000
        $bobotBaru = [];
        foreach ($kriteriaFilter as $k) {
            $bobotBaru[$k->id] = (float)$k->bobot_global / $totalBobotAsal;
        }

        // LANGKAH 2 SMART: Ambil skor parameter alternatif untuk mencari Nilai MAX dan MIN
        $nilaiRaw = NilaiAlternatif::whereIn('kriteria_id', $kriteriaTerpilihIds)->get();

        $skorPerKriteria = [];
        $nilaiSekolahMatrix = [];
        foreach ($nilaiRaw as $n) {
            $skorPerKriteria[$n->kriteria_id][] = (float)$n->skor_parameter;
            $nilaiSekolahMatrix[$n->sekolah_id][$n->kriteria_id] = (float)$n->skor_parameter;
        }

        // Cari C_Max dan C_Min untuk setiap kriteria terpilih
        $cMax = [];
        $cMin = [];
        foreach ($skorPerKriteria as $kritId => $kumpulanSkor) {
            $cMax[$kritId] = max($kumpulanSkor);
            $cMin[$kritId] = min($kumpulanSkor);
        }

        // Ambil seluruh data sekolah untuk dihitung skor akhirnya
        $sekolahAll = Sekolah::all();
        $hasilRekomendasi = [];

        // LANGKAH 3 & 4 SMART: Hitung Nilai Utilitas (U) & Nilai Akhir Per Sekolah
        foreach ($sekolahAll as $s) {
            $totalSkorSmart = 0;

            foreach ($kriteriaFilter as $k) {
                $skorAsli = $nilaiSekolahMatrix[$s->id][$k->id] ?? 0;
                $max = $cMax[$k->id] ?? 0;
                $min = $cMin[$k->id] ?? 0;

                // Rumus Nilai Utilitas SMART (Mencegah pembagian dengan nol jika max == min)
                if ($max == $min) {
                    $utilitas = 1.0;
                } else {
                    if ($k->tipe === 'benefit') {
                        $utilitas = ($skorAsli - $min) / ($max - $min);
                    } else {
                        // Jika tipe Cost (Jarak & Biaya)
                        $utilitas = ($max - $skorAsli) / ($max - $min);
                    }
                }

                // Akumulasi Total Skor (Utilitas x Bobot Baru Hasil Re-Normalisasi)
                $totalSkorSmart += $utilitas * $bobotBaru[$k->id];
            }

            $hasilRekomendasi[] = [
                'nama_sekolah' => $s->nama_sekolah,
                'skor_akhir' => $totalSkorSmart
            ];
        }

        // LANGKAH 5 SMART: Sorting hasil akhir dari nilai terbesar ke terkecil
        usort($hasilRekomendasi, function ($a, $b) {
            return $b['skor_akhir'] <=> $a['skor_akhir'];
        });

        // Potong array sesuai limit yang diinginkan
        return array_slice($hasilRekomendasi, 0, $limit);
    }
}

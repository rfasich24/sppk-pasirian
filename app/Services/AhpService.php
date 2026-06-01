<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\SppkUser;
use App\Models\JawabanKuesioner;
use Illuminate\Support\Facades\DB;

class AhpService
{
    public function hitungAhp(): array
    {
        // 1. Ambil data master kriteria & responden
        $kriteria = Kriteria::orderBy('id')->get();
        $users = SppkUser::all();

        $n = $kriteria->count(); // n = 6
        $jumlah_responden = $users->count(); // 5 responden

        if ($n === 0 || $jumlah_responden === 0) {
            return [];
        }

        // 2. Ambil semua jawaban kuesioner & bentuk matriks individu
        $jawaban = JawabanKuesioner::all();
        $matriks_individu = [];
        foreach ($jawaban as $j) {
            $matriks_individu[$j->user_id][$j->kriteria_id_1][$j->kriteria_id_2] = (float)$j->nilai_saaty;
        }

        // 3. TAHAP 1: Hitung Geometric Mean (Matriks Perbandingan Kolektif)
        $matriks_kolektif = [];
        $total_kolom = array_fill_keys($kriteria->pluck('id')->toArray(), 0.0);

        foreach ($kriteria as $row) {
            foreach ($kriteria as $col) {
                $r_id = $row->id;
                $c_id = $col->id;

                if ($r_id == $c_id) {
                    $matriks_kolektif[$r_id][$c_id] = 1.0;
                } else {
                    $perkalian_nilai = 1.0;

                    foreach ($users as $u) {
                        // Cek jika ada di upper triangle
                        if (isset($matriks_individu[$u->id][$r_id][$c_id])) {
                            $perkalian_nilai *= $matriks_individu[$u->id][$r_id][$c_id];
                        }
                        // Cek jika merupakan kebalikan (lower triangle)
                        elseif (isset($matriks_individu[$u->id][$c_id][$r_id])) {
                            $perkalian_nilai *= (1 / $matriks_individu[$u->id][$c_id][$r_id]);
                        }
                    }
                    // Akar pangkat n (n = jumlah responden)
                    $matriks_kolektif[$r_id][$c_id] = pow($perkalian_nilai, 1 / $jumlah_responden);
                }

                // Akumulasikan ke total kolom
                $total_kolom[$c_id] += $matriks_kolektif[$r_id][$c_id];
            }
        }

        // 4. TAHAP 2: Matriks Normalisasi & Hitung Bobot Kriteria (Eigenvector)
        $matriks_normalisasi = [];
        $bobot_kriteria = [];

        foreach ($kriteria as $row) {
            $r_id = $row->id;
            $jumlah_baris_normalisasi = 0;

            foreach ($kriteria as $col) {
                $c_id = $col->id;
                $nilai_norm = $matriks_kolektif[$r_id][$c_id] / $total_kolom[$c_id];
                $matriks_normalisasi[$r_id][$c_id] = $nilai_norm;

                $jumlah_baris_normalisasi += $nilai_norm;
            }
            // Rata-rata baris
            $bobot_kriteria[$r_id] = $jumlah_baris_normalisasi / $n;
        }

        // 5. TAHAP 3: Uji Konsistensi Logika
        $hasil_perkalian = [];
        $hasil_pembagian = [];
        $jumlah_rasio_konsistensi = 0;

        foreach ($kriteria as $row) {
            $r_id = $row->id;
            $total_perkalian_baris = 0;

            foreach ($kriteria as $col) {
                $c_id = $col->id;
                $total_perkalian_baris += $matriks_kolektif[$r_id][$c_id] * $bobot_kriteria[$c_id];
            }
            $hasil_perkalian[$r_id] = $total_perkalian_baris;
            $hasil_pembagian[$r_id] = $hasil_perkalian[$r_id] / $bobot_kriteria[$r_id];
            $jumlah_rasio_konsistensi += $hasil_pembagian[$r_id];
        }

        // Perhitungan lamba max, CI, dan CR
        $lambda_max = $jumlah_rasio_konsistensi / $n;
        $ci = ($lambda_max - $n) / ($n - 1);
        $ri = 1.24; // Nilai Random Index standar untuk n=6 kriteria
        $cr = $ci / $ri;
        $is_konsisten = $cr < 0.1;

        return compact(
            'kriteria',
            'matriks_kolektif',
            'total_kolom',
            'matriks_normalisasi',
            'bobot_kriteria',
            'hasil_perkalian',
            'hasil_pembagian',
            'lambda_max',
            'ci',
            'ri',
            'cr',
            'is_konsisten'
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SppkDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data lama dengan CASCADE RESTART IDENTITY
        DB::statement('TRUNCATE jawaban_kuesioner, nilai_alternatif, users, sekolah, kriteria RESTART IDENTITY CASCADE;');

        // 2. Suntik Data Master Kriteria
        $kriteria = [
            ['id' => 1, 'kode_kriteria' => 'K1', 'nama_kriteria' => 'Jarak', 'tipe' => 'cost', 'bobot_global' => 0.093340],
            ['id' => 2, 'kode_kriteria' => 'K2', 'nama_kriteria' => 'Fasilitas', 'tipe' => 'benefit', 'bobot_global' => 0.162208],
            ['id' => 3, 'kode_kriteria' => 'K3', 'nama_kriteria' => 'Biaya', 'tipe' => 'cost', 'bobot_global' => 0.101858],
            ['id' => 4, 'kode_kriteria' => 'K4', 'nama_kriteria' => 'Akreditasi', 'tipe' => 'benefit', 'bobot_global' => 0.292370],
            ['id' => 5, 'kode_kriteria' => 'K5', 'nama_kriteria' => 'Prestasi', 'tipe' => 'benefit', 'bobot_global' => 0.221294],
            ['id' => 6, 'kode_kriteria' => 'K6', 'nama_kriteria' => 'Ekstrakurikuler', 'tipe' => 'benefit', 'bobot_global' => 0.128929],
        ];
        DB::table('kriteria')->insert($kriteria);

        // 3. Suntik Data Sekolah
        $sekolah = [
            ['id' => 1, 'nama_sekolah' => 'SMP Negeri 1 Pasirian'],
            ['id' => 2, 'nama_sekolah' => 'SMP Negeri 2 Pasirian'],
            ['id' => 3, 'nama_sekolah' => 'SMP Negeri 3 Pasirian'],
            ['id' => 4, 'nama_sekolah' => 'SMP Kristen Pelangi Kasih Pasirian'],
            ['id' => 5, 'nama_sekolah' => 'SMP Negeri 4 Satu Atap'],
            ['id' => 6, 'nama_sekolah' => 'SMP Negeri 5 Satu Atap'],
            ['id' => 7, 'nama_sekolah' => 'SMP Negeri Satu Atap Gondoruso 1'],
            ['id' => 8, 'nama_sekolah' => 'SMP Islam Terpadu Al-Uswah'],
        ];
        DB::table('sekolah')->insert($sekolah);

        // 4. Suntik Data Responden (Users)
        $users = [
            ['id' => 1, 'nama_lengkap' => 'Rurut Kristina', 'status_responden' => 'Kepala Sekolah'],
            ['id' => 2, 'nama_lengkap' => 'Jadnika Dwi Rakhmawan Amrullah, S.Pd., M.Pd', 'status_responden' => 'Dosen'],
            ['id' => 3, 'nama_lengkap' => 'Zakaria Sandy P', 'status_responden' => 'Dosen'],
            ['id' => 4, 'nama_lengkap' => 'SUWARTONO', 'status_responden' => 'Guru'],
            ['id' => 5, 'nama_lengkap' => 'Vide Kurniawati', 'status_responden' => 'Wali Murid'],
        ];
        DB::table('users')->insert($users);

        // 5. Suntik Data Nilai Alternatif (Skor Parameter SMART)
        $nilai_alternatif = [
            ['sekolah_id'=>1,'kriteria_id'=>2,'skor_parameter'=>3.00],['sekolah_id'=>1,'kriteria_id'=>5,'skor_parameter'=>4.00],['sekolah_id'=>1,'kriteria_id'=>6,'skor_parameter'=>4.00],['sekolah_id'=>1,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>1,'kriteria_id'=>4,'skor_parameter'=>3.00],['sekolah_id'=>1,'kriteria_id'=>1,'skor_parameter'=>4.00],
            ['sekolah_id'=>2,'kriteria_id'=>2,'skor_parameter'=>3.00],['sekolah_id'=>2,'kriteria_id'=>5,'skor_parameter'=>4.00],['sekolah_id'=>2,'kriteria_id'=>6,'skor_parameter'=>4.00],['sekolah_id'=>2,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>2,'kriteria_id'=>4,'skor_parameter'=>3.00],['sekolah_id'=>2,'kriteria_id'=>1,'skor_parameter'=>4.00],
            ['sekolah_id'=>3,'kriteria_id'=>2,'skor_parameter'=>3.00],['sekolah_id'=>3,'kriteria_id'=>5,'skor_parameter'=>4.00],['sekolah_id'=>3,'kriteria_id'=>6,'skor_parameter'=>4.00],['sekolah_id'=>3,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>3,'kriteria_id'=>4,'skor_parameter'=>3.00],['sekolah_id'=>3,'kriteria_id'=>1,'skor_parameter'=>3.00],
            ['sekolah_id'=>4,'kriteria_id'=>2,'skor_parameter'=>3.00],['sekolah_id'=>4,'kriteria_id'=>5,'skor_parameter'=>4.00],['sekolah_id'=>4,'kriteria_id'=>6,'skor_parameter'=>4.00],['sekolah_id'=>4,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>4,'kriteria_id'=>4,'skor_parameter'=>2.00],['sekolah_id'=>4,'kriteria_id'=>1,'skor_parameter'=>4.00],
            ['sekolah_id'=>5,'kriteria_id'=>2,'skor_parameter'=>2.00],['sekolah_id'=>5,'kriteria_id'=>5,'skor_parameter'=>2.00],['sekolah_id'=>5,'kriteria_id'=>6,'skor_parameter'=>3.00],['sekolah_id'=>5,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>5,'kriteria_id'=>4,'skor_parameter'=>1.00],['sekolah_id'=>5,'kriteria_id'=>1,'skor_parameter'=>3.00],
            ['sekolah_id'=>6,'kriteria_id'=>2,'skor_parameter'=>2.00],['sekolah_id'=>6,'kriteria_id'=>5,'skor_parameter'=>3.00],['sekolah_id'=>6,'kriteria_id'=>6,'skor_parameter'=>4.00],['sekolah_id'=>6,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>6,'kriteria_id'=>4,'skor_parameter'=>2.00],['sekolah_id'=>6,'kriteria_id'=>1,'skor_parameter'=>1.00],
            ['sekolah_id'=>7,'kriteria_id'=>2,'skor_parameter'=>2.00],['sekolah_id'=>7,'kriteria_id'=>5,'skor_parameter'=>3.00],['sekolah_id'=>7,'kriteria_id'=>6,'skor_parameter'=>4.00],['sekolah_id'=>7,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>7,'kriteria_id'=>4,'skor_parameter'=>2.00],['sekolah_id'=>7,'kriteria_id'=>1,'skor_parameter'=>1.00],
            ['sekolah_id'=>8,'kriteria_id'=>2,'skor_parameter'=>2.00],['sekolah_id'=>8,'kriteria_id'=>5,'skor_parameter'=>4.00],['sekolah_id'=>8,'kriteria_id'=>6,'skor_parameter'=>4.00],['sekolah_id'=>8,'kriteria_id'=>3,'skor_parameter'=>5.00],['sekolah_id'=>8,'kriteria_id'=>4,'skor_parameter'=>3.00],['sekolah_id'=>8,'kriteria_id'=>1,'skor_parameter'=>4.00]
        ];
        DB::table('nilai_alternatif')->insert($nilai_alternatif);

        // 6. Suntik Data Jawaban Kuesioner (Matriks Upper Triangle 5 Responden)
        $jawaban = [
            ['user_id'=>1,'kriteria_id_1'=>2,'kriteria_id_2'=>1,'nilai_saaty'=>9.0000],['user_id'=>1,'kriteria_id_1'=>2,'kriteria_id_2'=>5,'nilai_saaty'=>0.2000],['user_id'=>1,'kriteria_id_1'=>2,'kriteria_id_2'=>6,'nilai_saaty'=>0.3333],['user_id'=>1,'kriteria_id_1'=>2,'kriteria_id_2'=>4,'nilai_saaty'=>0.2000],['user_id'=>1,'kriteria_id_1'=>2,'kriteria_id_2'=>3,'nilai_saaty'=>9.0000],['user_id'=>1,'kriteria_id_1'=>1,'kriteria_id_2'=>5,'nilai_saaty'=>0.2000],['user_id'=>1,'kriteria_id_1'=>1,'kriteria_id_2'=>6,'nilai_saaty'=>0.2000],['user_id'=>1,'kriteria_id_1'=>1,'kriteria_id_2'=>4,'nilai_saaty'=>0.2000],['user_id'=>1,'kriteria_id_1'=>1,'kriteria_id_2'=>3,'nilai_saaty'=>5.0000],['user_id'=>1,'kriteria_id_1'=>5,'kriteria_id_2'=>6,'nilai_saaty'=>5.0000],['user_id'=>1,'kriteria_id_1'=>5,'kriteria_id_2'=>4,'nilai_saaty'=>0.2000],['user_id'=>1,'kriteria_id_1'=>5,'kriteria_id_2'=>3,'nilai_saaty'=>5.0000],['user_id'=>1,'kriteria_id_1'=>6,'kriteria_id_2'=>4,'nilai_saaty'=>0.2000],['user_id'=>1,'kriteria_id_1'=>6,'kriteria_id_2'=>3,'nilai_saaty'=>5.0000],['user_id'=>1,'kriteria_id_1'=>4,'kriteria_id_2'=>3,'nilai_saaty'=>5.0000],
            ['user_id'=>2,'kriteria_id_1'=>2,'kriteria_id_2'=>1,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>2,'kriteria_id_2'=>5,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>2,'kriteria_id_2'=>6,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>2,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>2,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>1,'kriteria_id_2'=>5,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>1,'kriteria_id_2'=>6,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>1,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>1,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>5,'kriteria_id_2'=>6,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>5,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>5,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>6,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>6,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>2,'kriteria_id_1'=>4,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],
            ['user_id'=>3,'kriteria_id_1'=>2,'kriteria_id_2'=>1,'nilai_saaty'=>9.0000],['user_id'=>3,'kriteria_id_1'=>2,'kriteria_id_2'=>5,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>2,'kriteria_id_2'=>6,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>2,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>2,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>1,'kriteria_id_2'=>5,'nilai_saaty'=>0.1111],['user_id'=>3,'kriteria_id_1'=>1,'kriteria_id_2'=>6,'nilai_saaty'=>0.1111],['user_id'=>3,'kriteria_id_1'=>1,'kriteria_id_2'=>4,'nilai_saaty'=>0.1429],['user_id'=>3,'kriteria_id_1'=>1,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>5,'kriteria_id_2'=>6,'nilai_saaty'=>0.2000],['user_id'=>3,'kriteria_id_1'=>5,'kriteria_id_2'=>4,'nilai_saaty'=>5.0000],['user_id'=>3,'kriteria_id_1'=>5,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>6,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>6,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>3,'kriteria_id_1'=>4,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],
            ['user_id'=>4,'kriteria_id_1'=>2,'kriteria_id_2'=>1,'nilai_saaty'=>7.0000],['user_id'=>4,'kriteria_id_1'=>2,'kriteria_id_2'=>5,'nilai_saaty'=>0.2000],['user_id'=>4,'kriteria_id_1'=>2,'kriteria_id_2'=>6,'nilai_saaty'=>5.0000],['user_id'=>4,'kriteria_id_1'=>2,'kriteria_id_2'=>4,'nilai_saaty'=>0.1111],['user_id'=>4,'kriteria_id_1'=>2,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>4,'kriteria_id_1'=>1,'kriteria_id_2'=>5,'nilai_saaty'=>0.2000],['user_id'=>4,'kriteria_id_1'=>1,'kriteria_id_2'=>6,'nilai_saaty'=>5.0000],['user_id'=>4,'kriteria_id_1'=>1,'kriteria_id_2'=>4,'nilai_saaty'=>0.1111],['user_id'=>4,'kriteria_id_1'=>1,'kriteria_id_2'=>3,'nilai_saaty'=>7.0000],['user_id'=>4,'kriteria_id_1'=>5,'kriteria_id_2'=>6,'nilai_saaty'=>7.0000],['user_id'=>4,'kriteria_id_1'=>5,'kriteria_id_2'=>4,'nilai_saaty'=>0.1111],['user_id'=>4,'kriteria_id_1'=>5,'kriteria_id_2'=>3,'nilai_saaty'=>9.0000],['user_id'=>4,'kriteria_id_1'=>6,'kriteria_id_2'=>4,'nilai_saaty'=>0.1111],['user_id'=>4,'kriteria_id_1'=>6,'kriteria_id_2'=>3,'nilai_saaty'=>0.2000],['user_id'=>4,'kriteria_id_1'=>4,'kriteria_id_2'=>3,'nilai_saaty'=>9.0000],
            ['user_id'=>5,'kriteria_id_1'=>2,'kriteria_id_2'=>1,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>2,'kriteria_id_2'=>5,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>2,'kriteria_id_2'=>6,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>2,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>2,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>1,'kriteria_id_2'=>5,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>1,'kriteria_id_2'=>6,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>1,'kriteria_id_2'=>4,'nilai_saaty'=>0.3333],['user_id'=>5,'kriteria_id_1'=>1,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>5,'kriteria_id_2'=>6,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>5,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>5,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>6,'kriteria_id_2'=>4,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>6,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000],['user_id'=>5,'kriteria_id_1'=>4,'kriteria_id_2'=>3,'nilai_saaty'=>1.0000]
        ];
        DB::table('jawaban_kuesioner')->insert($jawaban);
    }
}

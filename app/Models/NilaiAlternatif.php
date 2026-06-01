<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NilaiAlternatif extends Model {
    protected $table = 'nilai_alternatif';
    public $timestamps = false;
    protected $fillable = ['sekolah_id', 'kriteria_id', 'skor_parameter'];

    public function kriteria() {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}

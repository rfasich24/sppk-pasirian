<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JawabanKuesioner extends Model {
    protected $table = 'jawaban_kuesioner';
    public $timestamps = false;
    protected $fillable = ['user_id', 'kriteria_id_1', 'kriteria_id_2', 'nilai_saaty'];
}

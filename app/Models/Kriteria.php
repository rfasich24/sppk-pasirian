<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model {
    protected $table = 'kriteria';
    public $timestamps = false;
    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'tipe', 'bobot_global'];
}

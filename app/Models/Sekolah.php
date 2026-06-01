<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model {
    protected $table = 'sekolah';
    public $timestamps = false;
    protected $fillable = ['nama_sekolah'];

    public function nilaiAlternatif() {
        return $this->hasMany(NilaiAlternatif::class, 'sekolah_id');
    }
}

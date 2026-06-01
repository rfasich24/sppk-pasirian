<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SppkUser extends Model {
    protected $table = 'users';
    public $timestamps = false;
    protected $fillable = ['nama_lengkap', 'status_responden'];
}

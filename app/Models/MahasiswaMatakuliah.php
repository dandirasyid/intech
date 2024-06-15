<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaMatakuliah extends Model {
    use HasFactory;

    protected $table = 'mahasiswa_matakuliah';
    protected $fillable = ['matakuliah_id', 'user_id', 'kelas_id'];

    public function matakuliah() {
        return $this->belongsTo(Matakuliah::class, 'matakuliah_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}

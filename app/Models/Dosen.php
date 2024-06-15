<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model {
    use HasFactory;

    protected $table = 'dosen';
    protected $fillable = ['user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function matakuliahs() {
        return $this->belongsToMany(Matakuliah::class, 'dosen_matakuliah', 'dosen_id', 'matakuliah_id')->withPivot('kelas_id');
    }

    public function dosenMatakuliahs() {
        return $this->hasMany(DosenMatakuliah::class);
    }

    public function kelas() {
        return $this->belongsToMany(Kelas::class, 'dosen_matakuliah', 'dosen_id', 'kelas_id')->withPivot('matakuliah_id');
    }

    public function materis() {
        return $this->hasMany(Materi::class);
    }

    public function tugas() {
        return $this->hasMany(Tugas::class);
    }
}

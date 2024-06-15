<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model {
    use HasFactory;

    protected $table = 'matakuliah';
    protected $fillable = [
        'image', 'name', 'deskripsi', 'semester',
    ];

    public function dosenMatakuliahs() {
        return $this->hasMany(DosenMatakuliah::class);
    }

    public function dosens() {
        return $this->belongsToMany(Dosen::class, 'dosen_matakuliah', 'matakuliah_id', 'dosen_id')->withPivot('kelas_id');
    }

    public function materis() {
        return $this->hasMany(Materi::class);
    }

    public function tugas() {
        return $this->hasMany(Tugas::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'mahasiswa_matakuliah', 'matakuliah_id', 'user_id')->withPivot('kelas_id');
    }
}

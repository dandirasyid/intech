<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model {
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable = ['name'];

    public function users() {
        return $this->hasMany(User::class, 'kelas_id');
    }

    public function dosens() {
        return $this->belongsToMany(Dosen::class, 'dosen_matakuliah', 'kelas_id', 'dosen_id')->withPivot('matakuliah_id');
    }

    public function matakuliahs() {
        return $this->belongsToMany(Matakuliah::class, 'kelas_matakuliah', 'kelas_id', 'matakuliah_id');
    }
}

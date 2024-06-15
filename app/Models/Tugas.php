<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model {
    use HasFactory;

    protected $table = 'tugas';
    protected $fillable = [
        'judul', 'deadline', 'deskripsi', 'file_path', 'image', 'matakuliah_id', 'dosen_id'
    ];

    public function matakuliah() {
        return $this->belongsTo(Matakuliah::class);
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }

    public function tugasSubmissions() {
        return $this->hasMany(TugasSubmission::class);
    }
}

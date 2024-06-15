<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model {
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'file_path',
        'image',
        'link_video',
        'matakuliah_id',
        'dosen_id'
    ];

    public function matakuliah() {
        return $this->belongsTo(Matakuliah::class);
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasSubmission extends Model {
    use HasFactory;

    protected $fillable = [
        'jawaban', 'file_path', 'nilai', 'tugas_id', 'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tugas() {
        return $this->belongsTo(Tugas::class);
    }
}

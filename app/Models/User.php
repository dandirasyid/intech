<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'no_telepon',
        'nim',
        'kelas_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function dosen() {
        return $this->hasOne(Dosen::class);
    }

    public function mahasiswa() {
        return $this->hasOne(Mahasiswa::class);
    }

    public function admin() {
        return $this->hasOne(Admin::class);
    }

    public function tugasSubmissions() {
        return $this->hasMany(TugasSubmission::class);
    }

    public function matakuliahs() {
        return $this->belongsToMany(Matakuliah::class, 'mahasiswa_matakuliah', 'user_id', 'matakuliah_id')
            ->withPivot('kelas_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;  //implementasi class authenticatable

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id'; //Mendefinisikan primary key dari tabel yang digunakan

    protected $fillable = ['level_id', 'username', 'nama', 'password', 'created_at', 'updated_at'];

    protected $hidden = ['password']; // jangan ditampilkan saat select

    protected $casts = ['password' => 'hashed']; // casting password agar otomatis dihash 

    // relasi ke tabel level
    public function level(): BelongsTo {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    // mendapatkan nama role

    public function getRoleName(): String {
        return $this->level->level_nama;
    }

    // cek apakah user memiliki role tertentu

    public function hasRole($role): bool {
        return $this->level->level_kode == $role;
    }

    // mendapatkan kode role

    public function getRole() {
        return $this->level->level_kode;
    }
}

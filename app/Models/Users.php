<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $table = 'users';
    protected $fillable = [
        'user_id',
        'nama',
        'user_name',
        'email',
        'nip',
        'nik',
        'password',
        'role_id',
        'tim_kerja_id',
        'no_urut',
        'npsn',
        'nama_satuan_pendidikan',
        'alamat_satuan_pendidikan',
        'kab_kota',
        'bos'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Get the tim kerja associated with the user.
     */
    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class, 'tim_kerja_id', 'tim_kerja_id');
    }
}

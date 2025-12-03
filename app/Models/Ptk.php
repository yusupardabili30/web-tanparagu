<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptk extends Model
{
    use HasFactory;

    protected $table = 'ptk';
    protected $primaryKey = 'ptk_id';

    // NONAKTIFKAN timestamps karena tabel tidak punya created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'nip',
        'nuptk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'jabatan',
        'agama',
        'pendidikan',
        'no_hp',
        'npwp',
        'email',
        'alamat_rumah',
        'alamat_rumah_kota',
        'instansi',
        'alamat_kantor',
        'alamat_kantor_kota',
        'no_rekening',
        'last_update'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'last_update' => 'datetime'
    ];
}

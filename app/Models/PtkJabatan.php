<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkJabatan extends Model
{
    use HasFactory;

    protected $table = 'ptk_jabatan';
    protected $primaryKey = 'id_jabatan';

    protected $fillable = [
        'id_jabatan',
        'nama_jabatan'
    ];

    public $timestamps = false;

    // Relasi ke Ptk
    public function ptks()
    {
        return $this->hasMany(Ptk::class, 'id_jabatan', 'id_jabatan');
    }
}

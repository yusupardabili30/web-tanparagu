<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PangkatJabatan extends Model
{
    use HasFactory;

    protected $table = 'pangkat_jabatan';
    protected $primaryKey = 'pangkat_jabatan_id';

    protected $fillable = [
        'golongan_ruang',
        'pangkat',
        'jenjang_jabatan'
    ];

    public $timestamps = false;

    public function ptk()
    {
        return $this->hasMany(Ptk::class, 'pangkat_jabatan_id', 'pangkat_jabatan_id');
    }
}

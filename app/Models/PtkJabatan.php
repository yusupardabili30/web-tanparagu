<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkJabatan extends Model
{
    use HasFactory;

    protected $table = 'ptk_jabatan';
    protected $primaryKey = 'id_jabatan';

    // NONAKTIFKAN timestamps
    public $timestamps = false;

    protected $fillable = [
        'nama_jabatan'
    ];
}

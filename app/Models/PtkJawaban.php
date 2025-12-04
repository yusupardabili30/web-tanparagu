<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkJawaban extends Model
{
    use HasFactory;

    protected $table = 'ptk_jawaban';
    protected $primaryKey = 'ptk_jawaban_id';
    public $timestamps = true;

    protected $fillable = [
        'kegiatan_id',
        'ptk_id',
        'instrumen_id',
        'indikator_id',
        'sub_indikator_id',
        'sub_indikator_code',
        'level',
        'created_at',
        'updated_at'
    ];
}

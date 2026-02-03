<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkJawabanRekomendasi extends Model
{
    use HasFactory;

    protected $table = 'ptk_jawaban_rekomendasi';
    protected $primaryKey = 'ptk_jawaban_rekomendasi_id';
    public $timestamps = true;

    protected $fillable = [
        'kegiatan_id',
        'tahap',
        'ptk_id',
        'instrumen_id',
        'indikator_id',
        'indikator_code',
        'sub_indikator_id',
        'sub_indikator_code',
        'level_gap',
        'created_at',
        'updated_at'
    ];
}

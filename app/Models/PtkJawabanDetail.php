<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkJawabanDetail extends Model
{
    use HasFactory;

    protected $table = 'ptk_jawaban_detail';
    protected $primaryKey = 'ptk_jawaban_detail_id';
    public $timestamps = true;

    protected $fillable = [
        'kegiatan_id',
        'tahap',
        'ptk_id',
        'soal_id',
        'instrumen_id',
        'indikator_id',
        'indikator_code',
        'sub_indikator_id',
        'sub_indikator_code',
        'level',
        'bobot',
        'time_start',
        'time_end',
        'selisih',
        'created_at',
        'updated_at'
    ];
}

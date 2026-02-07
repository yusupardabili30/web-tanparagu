<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkJawabanAdjusment extends Model
{
    use HasFactory;

    protected $table = 'ptk_jawaban_adjusment';
    protected $primaryKey = 'ptk_jawaban_adjusment_id';
    public $timestamps = true;

    protected $fillable = [
        'kegiatan_id',
        'tahap',
        'ptk_id',
        'instrumen_id',
        'level_adjusment',
        'created_at',
        'updated_at'
    ];
}

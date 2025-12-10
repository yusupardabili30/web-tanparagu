<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Soal;

class SoalJawaban extends Model
{
    use HasFactory;
    protected $table = 'soal_jawaban';
    protected $primaryKey = 'soal_jawaban_id';
    public $timestamps = false;


    protected $fillable = [
        'soal_jawaban_id',
        'soal_id',
        'pilihan_jawaban',
        'bobot'
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id', 'soal_id');
    }
}

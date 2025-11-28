<?php

namespace App\Models;

use App\Models\SoalJawaban;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soal extends Model
{
    use HasFactory;
    protected $table = 'soal';
    protected $primaryKey = 'soal_id';
    public $timestamps = false;


    protected $fillable = [
        'soal_id',
        'sub_indikator_id',
        'soal_case_id',
        'no_urut',
        'level',
        'soal'
    ];

    //Relasi ke jawaban soal (hasMany)
    public function soal_jawaban()
    {
        return $this->hasMany(SoalJawaban::class, 'soal_id', 'soal_id');
    }

    //Relasi ke case (belongsTo)
    public function soal_case()
    {
        return $this->belongsTo(SoalCase::class, 'soal_case_id', 'soal_case_id');
    }
}

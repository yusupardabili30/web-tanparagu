<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalCase extends Model
{
    use HasFactory;
    protected $table = 'soal_case';
    protected $primaryKey = 'soal_case_id';
    public $timestamps = false;


    protected $fillable = [
        'soal_case_id',
        'sub_indikator_id',
        'tittle',
        'case'
    ];

    //Relasi: 1 case punya banyak soal
    public function soal()
    {
        return $this->hasMany(Soal::class, 'soal_case_id', 'soal_case_id');
    }
}

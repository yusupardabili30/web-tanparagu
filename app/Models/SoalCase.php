<?php

namespace App\Models;

use App\Models\SubIndikator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoalCase extends Model
{
    use HasFactory;
    protected $table = 'soal_case';
    protected $primaryKey = 'soal_case_id';
    public $timestamps = false;


    protected $fillable = [
        'soal_case_id',
        'sub_indikator_id',
        'sub_indikator_code',
        'entity',        // ← Tambahkan ini
        'no_urut',       // ← Tambahkan ini
        'title',
        'case'
    ];

    //Relasi: 1 case punya banyak soal
    public function soal()
    {
        return $this->hasMany(Soal::class, 'soal_case_id', 'soal_case_id');
    }

    public function sub_indikator()
    {
        return $this->belongsTo(SubIndikator::class, 'sub_indikator_id', 'sub_indikator_id');
    }
}

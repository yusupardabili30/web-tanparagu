<?php
// app/Models/PtkRekomendasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtkRekomendasi extends Model
{
    use HasFactory;

    protected $table = 'ptk_rekomendasi';
    protected $primaryKey = 'ptk_rekomendasi_id';

    public $timestamps = false;

    protected $fillable = [
        'sub_indikator_id',
        'tahap',
        'entity',
        'sub_indikator_code',
        'level',
        'rekomendasi'
    ];

    protected $casts = [
        'level' => 'integer',
        'tahap' => 'integer',
    ];

    /**
     * Relasi ke sub indikator
     */
    public function subIndikator()
    {
        return $this->belongsTo(SubIndikator::class, 'sub_indikator_id', 'sub_indikator_id');
    }

    /**
     * Scope untuk filter berdasarkan level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope untuk filter berdasarkan sub indikator
     */
    public function scopeBySubIndikator($query, $subIndikatorId)
    {
        return $query->where('sub_indikator_id', $subIndikatorId);
    }

    /**
     * Scope untuk filter berdasarkan tahap
     */
    public function scopeByTahap($query, $tahap)
    {
        return $query->where('tahap', $tahap);
    }

    /**
     * Scope untuk filter berdasarkan entity
     */
    public function scopeByEntity($query, $entity)
    {
        return $query->where('entity', $entity);
    }

    /**
     * Mendapatkan nama level
     */
    public function getLevelNameAttribute()
    {
        $levelNames = [
            1 => 'Sangat Rendah',
            2 => 'Penerapan',
            3 => 'Analisis',
            4 => 'Evaluasi',
            5 => 'Pembimbingan Rekan Sejawat'
        ];

        return $levelNames[$this->level] ?? 'Tidak Diketahui';
    }

    /**
     * Mendapatkan warna untuk level
     */
    public function getLevelColorAttribute()
    {
        $levelColors = [
            1 => 'secondary',
            2 => 'info',
            3 => 'primary',
            4 => 'warning',
            5 => 'success'
        ];

        return $levelColors[$this->level] ?? 'secondary';
    }
}

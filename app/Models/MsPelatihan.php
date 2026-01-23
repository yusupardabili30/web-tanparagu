<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPelatihan extends Model
{
    protected $table = 'ms_pelatihan';
    protected $primaryKey = 'ms_pelatihan_id';

    protected $fillable = ['nama_pelatihan', 'entity']; // Tambah entity

    public $timestamps = false;

    /**
     * Relationship dengan PTK Pelatihan
     */
    public function ptkPelatihan()
    {
        return $this->hasMany(PtkPelatihan::class, 'ms_pelatihan_id', 'ms_pelatihan_id');
    }

    /**
     * Scope untuk pencarian berdasarkan nama pelatihan
     */
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where('nama_pelatihan', 'LIKE', "%{$keyword}%");
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan entity
     */
    public function scopeByEntity($query, $entity)
    {
        if ($entity) {
            return $query->where('entity', $entity);
        }
        return $query;
    }

    /**
     * Accessor untuk nama pelatihan dengan trim
     */
    public function getNamaPelatihanTrimmedAttribute()
    {
        return trim($this->nama_pelatihan);
    }
}

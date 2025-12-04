<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';
    protected $primaryKey = 'sekolah_id';

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'bentuk_pendidikan',
        'status_sekolah',
        'akreditasi',
        'kab_kota',
        'alamat'
    ];

    public $timestamps = false;

    public function ptk()
    {
        return $this->hasMany(Ptk::class, 'sekolah_id', 'sekolah_id');
    }

    // Method untuk format response API
    public function toApiArray()
    {
        return [
            'sekolah_id' => $this->sekolah_id,
            'npsn' => $this->npsn,
            'nama_sekolah' => $this->nama_sekolah,
            'bentuk_pendidikan' => $this->bentuk_pendidikan,
            'status_sekolah' => $this->status_sekolah,
            'akreditasi' => $this->akreditasi,
            'kab_kota' => $this->kab_kota,
            'alamat' => $this->alamat
        ];
    }
}

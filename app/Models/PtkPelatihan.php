<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PtkPelatihan extends Model
{
    protected $table = 'ptk_pelatihan';
    protected $primaryKey = 'ptk_pelatihan_id';

    protected $fillable = [
        'ptk_id',
        'kegiatan_id',
        'kategori_id',
        'ms_pelatihan_id',
        'pelatihan_lainnya'
    ];

    public $timestamps = false;
    protected $dates = ['created_at'];

    /**
     * Relationship dengan PTK
     */
    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id', 'ptk_id');
    }

    /**
     * Relationship dengan Kegiatan
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'kegiatan_id');
    }

    /**
     * Relationship dengan Master Pelatihan
     */
    public function masterPelatihan()
    {
        return $this->belongsTo(MsPelatihan::class, 'ms_pelatihan_id', 'ms_pelatihan_id');
    }

    /**
     * Accessor untuk kategori pelatihan
     */
    public function getKategoriPelatihanAttribute()
    {
        $kategori = [
            1 => 'Belum Tersedia',
            2 => 'Dari Daftar',
            3 => 'Lainnya'
        ];

        return $kategori[$this->kategori_id] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk nama pelatihan lengkap
     */
    public function getNamaPelatihanLengkapAttribute()
    {
        if ($this->kategori_id == 2 && $this->masterPelatihan) {
            return $this->masterPelatihan->nama_pelatihan;
        } elseif ($this->kategori_id == 3) {
            return $this->pelatihan_lainnya;
        } else {
            return 'Belum Tersedia';
        }
    }

    /**
     * Static method untuk mendapatkan pelatihan yang dipilih PTK
     */
    public static function getPelatihanByPtk($ptkId, $kegiatanId)
    {
        return self::where('ptk_id', $ptkId)
            ->where('kegiatan_id', $kegiatanId)
            ->get();
    }
}

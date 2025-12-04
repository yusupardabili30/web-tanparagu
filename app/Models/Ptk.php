<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptk extends Model
{
    use HasFactory;

    protected $table = 'ptk';
    protected $primaryKey = 'ptk_id';

    protected $fillable = [
        'ptk_id', // Primary key
        'nik',
        'nip',
        'nuptk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'pangkat_jabatan_id',
        'agama',
        'pendidikan',
        'no_hp',
        'npwp',
        'email',
        'alamat_rumah',
        'kota_id',
        'alamat_rumah_kota',
        'instansi',
        'sekolah_id',
        'alamat_kantor',
        'alamat_kantor_kota',
        'no_rekening',
        'instansi', // TAMBAHKAN INI
        'last_update'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'last_update' => 'datetime'
    ];

    public $timestamps = false;

    // Relasi ke PangkatJabatan
    public function pangkatJabatan()
    {
        return $this->belongsTo(PangkatJabatan::class, 'pangkat_jabatan_id', 'pangkat_jabatan_id');
    }

    // Relasi ke Kota
    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id', 'kota_id');
    }

    // Relasi ke Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }

    // Accessor untuk mendapatkan nama jabatan dari relasi pangkatJabatan
    public function getJabatanAttribute()
    {
        return $this->pangkatJabatan ? $this->pangkatJabatan->jenjang_jabatan : null;
    }

    // Accessor untuk mendapatkan nama kota dari relasi kota
    public function getNamaKotaAttribute()
    {
        return $this->kota ? $this->kota->nama_kota : $this->alamat_rumah_kota;
    }

    // Accessor untuk mendapatkan nama sekolah dari relasi sekolah
    public function getNamaSekolahAttribute()
    {
        return $this->sekolah ? $this->sekolah->nama_sekolah : $this->instansi;
    }

    // Accessor untuk mendapatkan golongan ruang
    public function getGolonganRuangAttribute()
    {
        return $this->pangkatJabatan ? $this->pangkatJabatan->golongan_ruang : null;
    }

    // Accessor untuk mendapatkan pangkat
    public function getPangkatAttribute()
    {
        return $this->pangkatJabatan ? $this->pangkatJabatan->pangkat : null;
    }

    // Accessor untuk format tanggal lahir
    public function getTglLahirFormattedAttribute()
    {
        return $this->tgl_lahir ? $this->tgl_lahir->format('d/m/Y') : null;
    }

    // Scope untuk mencari berdasarkan NIP
    public function scopeByNip($query, $nip)
    {
        return $query->where('nip', $nip);
    }

    // Scope untuk aktif (punya data lengkap)
    public function scopeAktif($query)
    {
        return $query->whereNotNull('nip')
            ->whereNotNull('nama')
            ->whereNotNull('email')
            ->whereNotNull('no_hp');
    }
    // Relasi ke PtkJawaban
    public function jawaban()
    {
        return $this->hasMany(PtkJawaban::class, 'ptk_id', 'ptk_id');
    }
}

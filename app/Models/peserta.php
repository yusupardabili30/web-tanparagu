<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peserta extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     *
     * @var string
     */
    protected $table = 'peserta';

    /**
     * Primary key tabel
     *
     * @var string
     */
    protected $primaryKey = 'peserta_id';

    /**
     * Nonaktifkan timestamps (created_at dan updated_at)
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi (mass assignment)
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'nik',
        'nip',
        'kegiatan_id',
        'email',
        'no_hp',
        'npwp',
        'ttd_base64',
        'ms_bank_id',
        'no_rekening',
        'atas_nama_rekening',
        'pangkat_jabatan_id',
        'agama',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'pendidikan',
        'sekolah_id',
        'instansi',
        'alamat_kantor',
        'kota_id',
        'provinsi',
        'last_update'
    ];

    /**
     * Tipe data untuk casting
     *
     * @var array
     */
    protected $casts = [
        'tgl_lahir' => 'date',
        'last_update' => 'datetime',
        'pangkat_jabatan_id' => 'integer',
        'sekolah_id' => 'integer',
        'kota_id' => 'integer',
    ];

    /**
     * Relasi ke tabel pangkat_jabatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pangkatJabatan()
    {
        return $this->belongsTo(PangkatJabatan::class, 'pangkat_jabatan_id', 'pangkat_jabatan_id');
    }

    /**
     * Relasi ke tabel sekolah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }

    /**
     * Relasi ke tabel kota
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id', 'kota_id');
    }

    /**
     * Scope untuk mencari peserta berdasarkan NIK
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nik
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }

    /**
     * Scope untuk mencari peserta berdasarkan NIP
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nip
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNip($query, $nip)
    {
        return $query->where('nip', $nip);
    }

    /**
     * Scope untuk mencari peserta berdasarkan email
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope untuk mencari peserta berdasarkan sekolah
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $sekolahId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySekolah($query, $sekolahId)
    {
        return $query->where('sekolah_id', $sekolahId);
    }

    /**
     * Mutator untuk mengubah format tanggal lahir saat menyimpan
     *
     * @param mixed $value
     * @return void
     */
    public function setTglLahirAttribute($value)
    {
        if ($value) {
            $this->attributes['tgl_lahir'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    /**
     * Accessor untuk mendapatkan format tanggal lahir yang mudah dibaca
     *
     * @param mixed $value
     * @return string|null
     */
    public function getTglLahirFormattedAttribute()
    {
        if ($this->tgl_lahir) {
            return Carbon::parse($this->tgl_lahir)->translatedFormat('d F Y');
        }
        return null;
    }

    /**
     * Accessor untuk mendapatkan usia peserta
     *
     * @return int|null
     */
    public function getUsiaAttribute()
    {
        if ($this->tgl_lahir) {
            return Carbon::parse($this->tgl_lahir)->age;
        }
        return null;
    }

    /**
     * Mutator untuk mengubah format last_update saat menyimpan
     *
     * @param mixed $value
     * @return void
     */
    public function setLastUpdateAttribute($value)
    {
        if ($value) {
            $this->attributes['last_update'] = Carbon::parse($value);
        } else {
            $this->attributes['last_update'] = Carbon::now();
        }
    }

    /**
     * Accessor untuk mendapatkan format last_update yang mudah dibaca
     *
     * @param mixed $value
     * @return string|null
     */
    public function getLastUpdateFormattedAttribute()
    {
        if ($this->last_update) {
            return Carbon::parse($this->last_update)->translatedFormat('d F Y H:i:s');
        }
        return null;
    }

    /**
     * Accessor untuk mendapatkan nama lengkap dengan gelar (jika ada)
     *
     * @return string
     */
    public function getNamaLengkapAttribute()
    {
        return $this->nama;
    }

    /**
     * Validasi keunikan NIK, NIP, dan email
     *
     * @return array
     */
    public static function validationRules($pesertaId = null)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'nip' => 'required|string|max:20',
            'email' => 'required|email|max:25',
            'no_hp' => 'required|string|max:20',
            'npwp' => 'required|string|max:30',
            'no_rekening' => 'required|string|max:45',
            'no_rekening' => 'required|string|max:45',
            'atas_nama_rekening' => 'required|string|max:255',
            'pangkat_jabatan_id' => 'required|integer',
            'agama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:2|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'pendidikan' => 'required|string|max:255',
            'sekolah_id' => 'required|integer',
            'instansi' => 'required|string|max:255',
            'alamat_kantor' => 'required|string|max:255',
            'kota_id' => 'required|integer',
            'provinsi' => 'required|string|max:255',
        ];

        // Tambahkan rule unique untuk NIK, NIP, dan email jika pesertaId diberikan
        if ($pesertaId) {
            $rules['nik'] .= '|unique:peserta,nik,' . $pesertaId . ',peserta_id';
            $rules['nip'] .= '|unique:peserta,nip,' . $pesertaId . ',peserta_id';
            $rules['email'] .= '|unique:peserta,email,' . $pesertaId . ',peserta_id';
        } else {
            $rules['nik'] .= '|unique:peserta,nik';
            $rules['nip'] .= '|unique:peserta,nip';
            $rules['email'] .= '|unique:peserta,email';
        }

        return $rules;
    }

    /**
     * Pesan validasi kustom
     *
     * @return array
     */
    public static function validationMessages()
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'npwp.required' => 'NPWP wajib diisi.',
            'ms_bank_id.required' => 'Nama bank wajib diisi.',
            'no_rekening.required' => 'Nomor rekening wajib diisi.',
            'atas_nama_rekening.required' => 'Atas nama rekening wajib diisi.',
            'pangkat_jabatan_id.required' => 'Pangkat/jabatan wajib dipilih.',
            'agama.required' => 'Agama wajib diisi.',
            'ms_bank_id.required' => 'Bank wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'pendidikan.required' => 'Pendidikan terakhir wajib diisi.',
            'sekolah_id.required' => 'Sekolah wajib dipilih.',
            'instansi.required' => 'Instansi wajib diisi.',
            'alamat_kantor.required' => 'Alamat kantor wajib diisi.',
            'kota_id.required' => 'Kota wajib dipilih.',
            'provinsi.required' => 'Provinsi wajib diisi.',
        ];
    }

    // Relasi ke tabel ms_bank
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'ms_bank_id', 'ms_bank_id');
    }


    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', '%' . $search . '%')
            ->orWhere('nik', 'like', '%' . $search . '%')
            ->orWhere('nip', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('no_hp', 'like', '%' . $search . '%');
    }

    public function scopeFilterByPangkatJabatan($query, $pangkatJabatanId)
    {
        if ($pangkatJabatanId) {
            return $query->where('pangkat_jabatan_id', $pangkatJabatanId);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan kota
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $kotaId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByKota($query, $kotaId)
    {
        if ($kotaId) {
            return $query->where('kota_id', $kotaId);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan sekolah
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $sekolahId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterBySekolah($query, $sekolahId)
    {
        if ($sekolahId) {
            return $query->where('sekolah_id', $sekolahId);
        }
        return $query;
    }

    /**
     * Method untuk mendapatkan data peserta dengan relasi lengkap
     *
     * @param int $pesertaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getWithRelations($pesertaId = null)
    {
        $query = self::with(['pangkatJabatan', 'sekolah', 'kota']);

        if ($pesertaId) {
            return $query->find($pesertaId);
        }

        return $query->get();
    }




    // Mutator untuk instansi - ubah null menjadi empty string
    public function setInstansiAttribute($value)
    {
        $this->attributes['instansi'] = $value ?? '';
    }

    // Mutator untuk sekolah_id - ubah empty string menjadi null
    public function setSekolahIdAttribute($value)
    {
        $this->attributes['sekolah_id'] = $value === '' ? null : $value;
    }

    // Accessor untuk instansi - tampilkan null jika empty string
    public function getInstansiAttribute($value)
    {
        return $value === '' ? null : $value;
    }
}

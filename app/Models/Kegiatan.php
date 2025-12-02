<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class Kegiatan extends Model
{
    use HasFactory;
    protected $table = 'kegiatan';
    protected $primaryKey = 'kegiatan_id';
    public $timestamps = false;

    protected $fillable = [
        'kegiatan_id',
        'kegiatan_name',
        'entity',
        'instrumen_url',
        'instrumen_token',
        'status', // Tetap pakai 'status'
        'start_date',
        'end_date',
        'created_at',
        'modified_at',
    ];

    // Method untuk generate token otomatis (unik) - 16 karakter
    public static function generateToken()
    {
        return substr(bin2hex(random_bytes(8)), 0, 6);
    }

    // Method untuk generate URL otomatis - menggunakan encoded kegiatan_id
    public static function generateUrl($kegiatan_id)
    {
        $encoded = Hashids::encode($kegiatan_id);
        return 'lockscreen/' . $encoded;
    }

    // Method untuk cek status aktif berdasarkan status
    public function isActive()
    {
        return $this->status === 'Active';
    }

    // Method untuk reset token
    public function resetToken()
    {
        $this->instrumen_token = self::generateToken();
        $this->modified_at = Carbon::now()->format('Y-m-d H:i:s');
        $this->save();
    }

    // Hapus method-method yang terkait dengan tanggal kadaluarsa
}

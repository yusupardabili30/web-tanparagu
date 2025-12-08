<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kota';
    protected $primaryKey = 'kota_id';

    protected $fillable = [
        'nama_kota'
    ];

    public $timestamps = false;

    public function ptk()
    {
        return $this->hasMany(Ptk::class, 'kota_id', 'kota_id');
    }
}

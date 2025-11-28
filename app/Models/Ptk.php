<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptk extends Model
{
    use HasFactory;
    protected $table = 'ptk';
    protected $primaryKey = 'ptk_id';
    public $timestamps = false;

    protected $fillable = [
        'ptk_id',
        'nik',
        'nip',
        'nuptk',
        'nama'
    ];
}

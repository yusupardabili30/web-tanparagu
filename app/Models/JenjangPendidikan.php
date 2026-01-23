<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenjangPendidikan extends Model
{
    use HasFactory;

    protected $table = 'jenjang_pendidikan';
    protected $primaryKey = 'jenjang_pendidikan_id';

    protected $fillable = [
        'jenjang_pendidikan_id',
        'jenjang_pendidikan'

    ];
}

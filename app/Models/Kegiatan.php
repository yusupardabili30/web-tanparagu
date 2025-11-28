<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status',
        'start_date',
        'end_date',
        'created_at',
        'modified_at',
    ];
}

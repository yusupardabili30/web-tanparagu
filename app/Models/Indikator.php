<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Indikator extends Model
{
    use HasFactory;
    protected $table = 'indikator';
    protected $primaryKey = 'indikator_id';
    public $timestamps = false;

    protected $fillable = [
        'indikator_id',
        'instrumen_id',
        'tahap',
        'no_urut',
        'indikator_code',
        'indikator_name'
    ];
}

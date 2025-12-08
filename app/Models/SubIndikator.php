<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubIndikator extends Model
{
    use HasFactory;
    protected $table = 'sub_indikator';
    protected $primaryKey = 'sub_indikator_id';
    public $timestamps = false;

    protected $fillable = [
        'sub_indikator_id',
        'instrumen_id',
        'indikator_id',
        'no_urut',
        'indikator_code',
        'sub_indikator_code',
        'sub_indikator_name'
    ];
}

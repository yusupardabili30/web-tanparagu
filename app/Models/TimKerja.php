<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKerja extends Model
{
    use HasFactory;

    protected $primaryKey = 'tim_kerja_id';
    public $timestamps = false;

    protected $table = 'tim_kerja';
    protected $fillable = [
        'tim_kerja',
        'tim_kerja_desc'
    ];
}

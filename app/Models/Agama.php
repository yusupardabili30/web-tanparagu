<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    use HasFactory;

    protected $table = 'agama';
    protected $primaryKey = 'agama_id';

    protected $fillable = [
        'nama_agama'
    ];

    public $timestamps = false;
}

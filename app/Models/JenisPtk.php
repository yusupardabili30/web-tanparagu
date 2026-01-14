<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPtk extends Model
{
    use HasFactory;

    protected $table = 'jenis_ptk';
    protected $primaryKey = 'jenis_ptk_id';

    protected $fillable = ['jenis_ptk'];

    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PangkatGolongan extends Model
{
    use HasFactory;

    protected $table = 'pangkat_golongan';
    protected $primaryKey = 'pangkat_golongan_id';

    protected $fillable = ['pangkat', 'golongan', 'deskripsi'];

    public $timestamps = false;
}

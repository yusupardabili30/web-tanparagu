<?php
// app/Models/Bank.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Bank extends Model
{
    protected $table = 'ms_bank';
    protected $primaryKey = 'ms_bank_id';
    public $timestamps = false;

    protected $fillable = [
        'ms_bank_id',
        'nama_bank',
        'kode_bank'

    ];
}

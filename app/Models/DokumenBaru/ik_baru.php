<?php

namespace App\Models\DokumenBaru;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ik_baru extends Model
{
    use HasFactory;
    protected $table = 'ik_barus';

    protected $guarded = [];

    protected $casts = [
        'aktifitas_tanggung_jawab' => 'array',
        'people' => 'array',
    ];
}

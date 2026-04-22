<?php

namespace App\Models\DokumenBaru;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sp_baru extends Model
{
    use HasFactory;
    protected $table = 'sp_barus';

    protected $guarded = [];

    protected $casts = [
        'referensi' => 'array',
        'definisi' => 'array',
        'aktifitas_tanggung_jawab' => 'array',
        'lampiran' => 'array',
        'people' => 'array',
    ];
}

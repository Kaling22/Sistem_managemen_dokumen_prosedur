<?php

namespace App\Models\DokumenBaru\JSA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jsa_baru extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function steps() {
    return $this->hasMany(jsa_step_baru::class,'jsa_id');
}
}

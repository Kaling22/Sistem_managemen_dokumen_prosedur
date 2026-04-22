<?php

namespace App\Models\DokumenBaru\JSA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jsa_hazard_baru extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function controls() {
    return $this->hasMany(jsa_control_baru::class,'jsa_hazard_id');
    }
}

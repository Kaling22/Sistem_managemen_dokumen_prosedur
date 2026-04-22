<?php

namespace App\Models\DokumenBaru\JSA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jsa_step_baru extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function hazards() {
        return $this->hasMany(jsa_hazard_baru::class,'jsa_step_id');
    }
}

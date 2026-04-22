<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJsaHazardBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsa_hazard_barus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jsa_step_id')->constrained('jsa_step_barus')->onDelete('cascade');
            $table->string('hazard_no'); // 1.1, 1.2
            $table->text('hazard_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jsa_hazard_barus');
    }
}

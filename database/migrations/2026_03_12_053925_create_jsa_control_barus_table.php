<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJsaControlBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsa_control_barus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jsa_hazard_id')->constrained('jsa_hazard_barus')->onDelete('cascade');
            $table->string('control_no'); // 1.1.1, 1.1.2
            $table->text('control_description');
            $table->boolean('approved')->default(false);
            $table->text('feedback')->nullable();
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
        Schema::dropIfExists('jsa_control_barus');
    }
}

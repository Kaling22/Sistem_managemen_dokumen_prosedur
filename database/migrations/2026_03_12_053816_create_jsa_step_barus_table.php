<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJsaStepBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsa_step_barus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jsa_id')->constrained('jsa_barus')->onDelete('cascade');
            $table->integer('step_no'); // 1, 2, 3...
            $table->text('description'); // Contoh: Mempersiapkan unit angkut
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
        Schema::dropIfExists('jsa_step_barus');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSopCoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_sop_coes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('no_dokumen');
            $table->string('judul_sop');
            $table->string('file_sop');
            $table->string('edisi');
            $table->string('revisi');
            $table->string('tanggal_efektif');
            $table->integer('views')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_sop_coes');
    }
}

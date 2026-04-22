<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSpShesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_sp_shes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('no_dokumen');
            $table->string('judul');
            $table->string('file')->nullable();
            $table->string('jenis_doc');
            $table->string('departemen');
            $table->string('pembuat');
            $table->string('DHdanSH')->nullable();
            $table->string('DHdanSHApprove')->nullable();
            $table->string('DHdanSHFeedback')->nullable();
            $table->string('status_doc');
            $table->date('pembuat_date')->nullable();
            $table->date('dhsh_date')->nullable();
            $table->longText('tujuan')->nullable();
            $table->longText('ruang_lingkup')->nullable();
            $table->longText('referensi')->nullable();
            $table->longText('definisi')->nullable();
            $table->longText('aktifitas_tanggung_jawab')->nullable();
            $table->longText('lampiran')->nullable();
            $table->string('edisi');
            $table->string('revisi');
            $table->date('efektif_date')->nullable();
            $table->integer('views')->nullable();
            $table->string('people')->nullable();
            $table->string('people_approve')->nullable();
            $table->date('people_date')->nullable();
            $table->string('catatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_sp_shes');
    }
}

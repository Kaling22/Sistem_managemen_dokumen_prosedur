<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSopBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sop_barus', function (Blueprint $table) {
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
            $table->string('PJO')->nullable();
            $table->string('PJOApprove')->nullable();
            $table->string('PJOFeedback')->nullable();
            $table->string('status_doc');
            $table->date('pembuat_date')->nullable();
            $table->date('dhsh_date')->nullable();
            $table->date('pjo_date')->nullable();
            $table->date('efektif_date')->nullable();
            // Dilanjutkan dengan kolom lainnya sesuai kebutuhan dokumen
            $table->longText('tujuan')->nullable();
            $table->longText('ruang_lingkup')->nullable();
            $table->longText('referensi')->nullable();
            $table->longText('definisi')->nullable();
            $table->longText('aktifitas_tanggung_jawab')->nullable();
            $table->longText('lampiran')->nullable();
            $table->string('people')->nullable();
            $table->longText('people_approve')->nullable();
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
        Schema::dropIfExists('sop_barus');
    }
}

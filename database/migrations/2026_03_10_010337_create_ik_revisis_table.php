<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIkRevisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ik_revisis', function (Blueprint $table) {
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
            $table->date('efektif_date')->nullable();
            // Dilanjutkan dengan kolom lainnya sesuai kebutuhan dokumen
            $table->longText('aktifitas_tanggung_jawab')->nullable();
            $table->string('edisi')->nullable();
            $table->string('revisi')->nullable();
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
        Schema::dropIfExists('ik_revisis');
    }
}

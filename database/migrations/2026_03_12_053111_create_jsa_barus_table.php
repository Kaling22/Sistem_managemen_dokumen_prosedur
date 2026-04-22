<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJsaBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsa_barus', function (Blueprint $table) {
            $table->id();
            $table->string('no_dokumen'); // PPA-ADRO-F-SHE-03B
            $table->string('revisi')->default('00');
            $table->date('tgl_terbit');
            $table->string('no_jsa'); // PPA-ADRO-JSA-COE-22
            $table->date('tgl_pembuatan');
            $table->string('nama_pekerjaan');
            $table->string('jenis_doc')->default('JSA');
            $table->string('lokasi_kerja');
            $table->string('departemen');
            $table->string('file')->nullable();
            $table->string('status_doc')->nullable();
            $table->text('apd_wajib');
            $table->text('peralatan_pendukung');
            //Pembuat
            $table->string('dibuat_oleh');
            $table->string('dibuat_oleh_approve')->nullable();
            //Reviewer 
            $table->string('direview_oleh');
            $table->string('direview_oleh_approve')->nullable();
            //DH/SH
            $table->string('disetujui_oleh');
            $table->string('disetujui_oleh_approve')->nullable();
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
        Schema::dropIfExists('jsa_barus');
    }
}

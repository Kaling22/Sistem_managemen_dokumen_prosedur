<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportPrdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_prds', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('report_number');
            $table->string('doc_number');
            $table->string('doc_name');
            $table->string('name');
            $table->string('nrp');
            $table->string('isi_report');
            $table->string('feedback')->nullable();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_prds');
    }
}

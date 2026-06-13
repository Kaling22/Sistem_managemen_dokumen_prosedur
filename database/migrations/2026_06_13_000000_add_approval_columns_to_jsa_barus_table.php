<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalColumnsToJsaBarusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jsa_barus', function (Blueprint $table) {
            if (!Schema::hasColumn('jsa_barus', 'edisi')) {
                $table->string('edisi')->default('1')->after('revisi');
            }
            if (!Schema::hasColumn('jsa_barus', 'direview_oleh_feedback')) {
                $table->text('direview_oleh_feedback')->nullable()->after('direview_oleh_approve');
            }
            if (!Schema::hasColumn('jsa_barus', 'direview_date')) {
                $table->dateTime('direview_date')->nullable()->after('direview_oleh_feedback');
            }
            if (!Schema::hasColumn('jsa_barus', 'disetujui_oleh_feedback')) {
                $table->text('disetujui_oleh_feedback')->nullable()->after('disetujui_oleh_approve');
            }
            if (!Schema::hasColumn('jsa_barus', 'disetujui_date')) {
                $table->dateTime('disetujui_date')->nullable()->after('disetujui_oleh_feedback');
            }
            if (!Schema::hasColumn('jsa_barus', 'efektif_date')) {
                $table->date('efektif_date')->nullable()->after('disetujui_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jsa_barus', function (Blueprint $table) {
            $table->dropColumn([
                'edisi',
                'direview_oleh_feedback',
                'direview_date',
                'disetujui_oleh_feedback',
                'disetujui_date',
                'efektif_date',
            ]);
        });
    }
}

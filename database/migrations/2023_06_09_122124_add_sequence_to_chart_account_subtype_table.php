<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSequenceToChartAccountSubtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chart_account_subtypes', function (Blueprint $table) {
            $table->integer('sequence')->length(10)->unsigned()->after('chart_account_subtype')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_account_subtypes', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
    }
}

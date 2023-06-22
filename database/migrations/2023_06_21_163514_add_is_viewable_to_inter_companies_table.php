<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsViewableToInterCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inter_companies', function (Blueprint $table) {
            $table->tinyInteger('is_viewable')->length(3)->unsigned()->after('inter_company_name')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inter_companies', function (Blueprint $table) {
            $table->dropColumn('is_viewable');
        });
    }
}

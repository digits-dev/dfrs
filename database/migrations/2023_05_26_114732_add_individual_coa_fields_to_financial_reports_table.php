<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndividualCoaFieldsToFinancialReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            $table->string('company',10)->after('chart_account')->nullable();
            $table->string('location',10)->after('company')->nullable();
            $table->string('department',10)->after('location')->nullable();
            $table->string('account',10)->after('department')->nullable();
            $table->string('customer',10)->after('account')->nullable();
            $table->string('brand',10)->after('customer')->nullable();
            $table->string('product',10)->after('brand')->nullable();
            $table->string('interco',10)->after('product')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->dropColumn('location');
            $table->dropColumn('department');
            $table->dropColumn('account');
            $table->dropColumn('customer');
            $table->dropColumn('brand');
            $table->dropColumn('product');
            $table->dropColumn('interco');
        });
    }
}

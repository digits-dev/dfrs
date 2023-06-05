<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_account_types', function (Blueprint $table) {
            $table->id();
            $table->string('chart_account_type',150)->nullable();
            $table->string('status', 10)->default('ACTIVE')->nullable();
            $table->integer('created_by', false, true)->length(10)->unsigned()->nullable();
            $table->integer('updated_by', false, true)->length(10)->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_account_types');
    }
}

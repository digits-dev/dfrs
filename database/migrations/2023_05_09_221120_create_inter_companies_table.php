<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inter_companies', function (Blueprint $table) {
            $table->id();
            $table->string('inter_company_code',20)->nullable();
            $table->string('inter_company_name',150)->nullable();
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
        Schema::dropIfExists('inter_companies');
    }
}

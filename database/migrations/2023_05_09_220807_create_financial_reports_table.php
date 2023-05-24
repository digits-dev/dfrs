<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->date('invoice_date')->nullable();
            $table->string('invoice_number',150)->nullable();
            $table->integer('invoice_types_id')->length(10)->unsigned()->nullable();
            $table->string('voucher_number',150)->nullable();
            $table->integer('trading_partners_id')->length(10)->unsigned()->nullable();
            $table->integer('invoice_statuses_id')->length(10)->unsigned()->nullable();
            $table->integer('payment_statuses_id')->length(10)->unsigned()->nullable();
            $table->string('is_accounted',3)->default('Y')->nullable();
            $table->decimal('amount',16,2)->nullable();
            $table->integer('currencies_id')->length(10)->unsigned()->nullable();
            $table->decimal('exchange_rate',16,2)->nullable();
            $table->date('exchange_date')->nullable();
            $table->decimal('invoice_amount',16,2)->nullable();
            $table->string('po_number',150)->nullable();
            $table->date('gl_date')->nullable();
            $table->string('description',150)->nullable();
            $table->string('chart_account',150)->nullable();
            $table->integer('created_by')->length(10)->unsigned()->nullable();
            $table->integer('updated_by')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('financial_reports');
    }
}

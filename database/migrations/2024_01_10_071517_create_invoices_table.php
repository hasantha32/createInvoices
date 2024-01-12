<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_title');
            $table->integer('invoice_number');
            $table->string('due_date');
            $table->string('additional_note');
            $table->string('status')->default('Pending');
            $table->string('totalFinalCost')->default('0.00'); // Set default value

            $table->unsignedBigInteger('customer_id'); // Add customer_id field
            $table->foreign('customer_id')->references('id')->on('customers');

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
        Schema::dropIfExists('invoices');
    }


}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('designer_id');
            $table->foreign('designer_id')->references('id')->on('designers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('header_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('product_package_id');
            $table->foreign('product_package_id')->references('id')->on('product_packages')->onUpdate('cascade')->onDelete('cascade');
            $table->string('deadline');
            $table->string('status')->default(0);
            $table->integer('quantity');
            $table->string('request_file_link');
            $table->string('notes')->nullable();
            $table->string('result_design')->nullable();
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
        Schema::dropIfExists('detail_orders');
    }
}

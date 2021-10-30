<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('product_package_id');
            $table->foreign('product_package_id')->references('id')->on('product_packages')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->foreign('designer_id')->references('id')->on('designers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('request_file_link')->nullable();
            $table->integer('quantity');
            $table->string('notes')->nullable();
            $table->string('deadline');
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
        Schema::dropIfExists('carts');
    }
}

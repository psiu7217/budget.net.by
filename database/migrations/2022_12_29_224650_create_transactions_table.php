<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rate');
            $table->unsignedBigInteger('from_purse_id');
            $table->unsignedBigInteger('to_purse_id');
            $table->timestamps();

            $table->foreign('from_purse_id')->references('id')->on('purses');
            $table->foreign('to_purse_id')->references('id')->on('purses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};

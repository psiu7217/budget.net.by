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
        Schema::table('purses', function (Blueprint $table) {
            $table->boolean('piggy_bank')->nullable()->default(false);
            $table->float('cash')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purses', function (Blueprint $table) {
            $table->dropColumn('piggy_bank');
            $table->dropColumn('cash');
        });
    }
};

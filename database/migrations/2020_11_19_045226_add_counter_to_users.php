<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCounterToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::connection("mongo")->table('users', function (Blueprint $table) {
        //     $table->integer("counter")->default(0);
        // });
        Schema::connection("mysql")->table('users', function (Blueprint $table) {
            $table->integer("counter")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::connection("mongo")->table('users', function (Blueprint $table) {
        //     $table->dropColumn("counter");
        // });
        Schema::connection("mysql")->table('users', function (Blueprint $table) {
            $table->dropColumn("counter");
        });
    }
}

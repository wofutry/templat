<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_user_level')->references('id')->on('user_levels');
        });

        // Schema::table('products', function (Blueprint $table) {
        //     $table->foreign('id_product_category')->references('id')->on('product_categories');
        //     $table->foreign('id_product_unit')->references('id')->on('product_units');
        // });

        // Schema::table('store_users', function (Blueprint $table) {
        //     $table->foreign('id_store')->references('id')->on('stores');
        //     $table->foreign('id_user')->references('id')->on('users');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relation');
    }
}

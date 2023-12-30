<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class Countries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('countries', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name')->nullable();
      $table->string('code')->nullable();
      $table->string('phonecode')->nullable();
      $table->string('currencyCode')->nullable();
      $table->string('currencySymbol')->nullable();
      $table->string('timezone')->nullable();
      $table->tinyinteger('status')->nullable();
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
      Schema::dropIfExists('countries');

    }
}

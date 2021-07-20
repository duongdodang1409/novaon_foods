<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuFoodsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('menu_foods', function (Blueprint $table) {
      $table->increments('id');

      $table->integer('id_food')->unsigned()->index();
      $table->foreign('id_food')->references('id')->on('foods')->onDelete('cascade');
      $table->integer('id_menu')->unsigned()->index();
      $table->foreign('id_menu')->references('id')->on('weekdays')->onDelete('cascade');
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
    Schema::dropIfExists('menu_foods');
  }
}

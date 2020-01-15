<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_field', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('field_id');

            $table->string('value')->nullable();

            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers');
            $table->foreign('field_id')->references('id')->on('fields');

            $table->unique(['subscriber_id', 'field_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriber_field');
    }
}

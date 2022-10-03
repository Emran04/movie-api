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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 300);
            $table->string('poster', 400);
            $table->year('release_year')->nullable();
            $table->timestamp('rent_from')->nullable();
            $table->timestamp('rent_to')->nullable();
            $table->double('rent_price')->default(0);
            $table->string('plan', 15)->default('basic');
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
        Schema::dropIfExists('movies');
    }
};

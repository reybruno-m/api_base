<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFunctionsProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('functions_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('function_id');
            $table->unsignedBigInteger('profile_id');
            $table->timestamps();

            $table->foreign('function_id')->references('id')->on('functions');
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('functions_profiles');
    }
}

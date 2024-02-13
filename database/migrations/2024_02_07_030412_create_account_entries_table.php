<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['i', 'e']);
            $table->date('date');
            $table->decimal('amount', $precision = 12, $scale = 2);
            $table->mediumText('concept');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('paid_method_id');
            $table->date('expiration_1')->default(null);
            $table->date('expiration_2')->default(null);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('paid_method_id')->references('id')->on('paid_methods');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_entries');
    }
}

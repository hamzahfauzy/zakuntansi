<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_account_id')->unsigned()->nullable();
            $table->string('account_code');
            $table->string('account_transaction_code');
            $table->string('name');
            $table->string('pos'); // Nrc, Lr
            $table->string('normal_balance'); // Debt, Cr
            $table->decimal('balance',15,2)->default(0);
            $table->timestamps();
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('parent_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}

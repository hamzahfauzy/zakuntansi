<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('account_code');
            $table->string('name');
            $table->string('pos'); // Nrc, Lr
            $table->string('normal_balance'); // Debt, Cr
            $table->timestamps();
        });

        Schema::table('ref_accounts', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('ref_accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_accounts');
    }
}

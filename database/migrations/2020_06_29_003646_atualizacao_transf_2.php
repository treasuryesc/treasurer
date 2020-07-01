<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AtualizacaoTransf2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // adicionar campo para valor da transferência com sinal + / -
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('transfer_account')->nullable();
        });
        // adicionar indices unico na transfer
        Schema::table('transfers', function (Blueprint $table) {
            $table->unique('expense_transaction_id');
            $table->unique('income_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transfer_account');
        });
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropUnique('transfers_expense_transaction_id_unique');
            $table->dropUnique('transfers_income_transaction_id_unique');
        });
    }
}

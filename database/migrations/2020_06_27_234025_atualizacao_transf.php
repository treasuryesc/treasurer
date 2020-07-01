<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AtualizacaoTransf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // atualiza a categoria - Transferência com type = transfer        
        DB::table('categories')
        ->where('type', 'other')
        ->update(['type' => 'transfer']);    
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::table('categories')
        ->where('type', 'transfer')
        ->update(['type' => 'other']);    
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToTutorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->string('address');
            $table->string('lga');
            $table->string('state');
            $table->string('bank_no');
            $table->string('bank_name');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('lga');
            $table->dropColumn('state');
            $table->dropColumn('bank_no');
            $table->dropColumn('bank_name');
        });
    }
}

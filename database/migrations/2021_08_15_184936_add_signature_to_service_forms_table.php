<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatureToServiceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services_forms', function (Blueprint $table) {
            $table->binary('signature')->nullable();
            $table->boolean('need_signature')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services_forms', function (Blueprint $table) {
            $table->dropColumn('signature');
            $table->dropColumn('need_signature');
        });
    }
}

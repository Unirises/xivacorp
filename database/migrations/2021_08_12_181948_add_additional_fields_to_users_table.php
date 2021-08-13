<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->default('');
            $table->string('first_name')->default('');
            $table->string('middle_name')->nullable()->default(null);
            $table->string('street_address')->default('');
            $table->string('barangay')->default('');
            $table->string('region')->default('');
            $table->dropColumn('name');
            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('street_address');
            $table->dropColumn('barangay');
            $table->dropColumn('region');
        });
    }
}

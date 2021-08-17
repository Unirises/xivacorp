<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('hcp_id')->nullable();
            $table->foreign('hcp_id')->references('id')->on('users');
            $table->string('workspace_id');
            $table->foreign('workspace_id')->references('code')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('service_id');
        $table->foreign('service_id')->references('id')->on('types');
            $table->dateTime('schedule')->nullable();
            $table->boolean('pending')->default(true);
            $table->timestamps();
        });

        Schema::table('diaries', function (Blueprint $table) {
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->foreign('consultation_id')->references('id')->on('services');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->foreign('consultation_id')->references('id')->on('services');
            $table->unsignedBigInteger('hcp_id')->nullable();
            $table->foreign('hcp_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}

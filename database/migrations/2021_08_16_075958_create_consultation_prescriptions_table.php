<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->longText('prescription');
            $table->longText('referral')->nullable();
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->foreign('consultation_id')->references('id')->on('consultations');
            $table->unsignedBigInteger('hcp_id')->nullable();
            $table->foreign('hcp_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultation_prescriptions');
    }
}

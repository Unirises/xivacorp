<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationFormPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_form', function (Blueprint $table) {
            $table->unsignedBigInteger('consultation_id');
            $table->foreign('consultation_id')->references('id')->on('consultations');
            $table->unsignedBigInteger('form_id');
            $table->foreign('form_id')->references('id')->on('forms');
            $table->boolean('required')->default(false);
            $table->unsignedBigInteger('answerable_by');
            $table->foreign('answerable_by')->references('id')->on('users');
            $table->primary(['answerable_by', 'consultation_id', 'form_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultation_form');
    }
}

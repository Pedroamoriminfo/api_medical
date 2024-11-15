<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdherenceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adherence_reports', function (Blueprint $table) {
            $table->id();
            $table->date('generation_date');
            $table->string('status'); // Exemplo: completo, parcial, ou ausente
            $table->foreignId('medication_id')->constrained('medications')->onDelete('cascade');
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
        Schema::dropIfExists('adherence_reports');
    }
}

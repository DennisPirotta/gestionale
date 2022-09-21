<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_report_details', function (Blueprint $table) {
            $table->id();
            $table  ->foreignId('hour_id')
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            $table  ->foreignId('technical_report_id')
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

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
        Schema::dropIfExists('technical_report_details');
    }
};

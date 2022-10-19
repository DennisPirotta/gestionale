<?php

use Carbon\Carbon;
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
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();

            $table  ->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

            $table->string('week_day');
            $table->time('morning_start')->default(Carbon::parse('8:00'));
            $table->time('morning_end')->default(Carbon::parse('12:30'));
            $table->time('afternoon_start')->default(Carbon::parse('13:30'));
            $table->time('afternoon_end')->default(Carbon::parse('17:00'));
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
        Schema::dropIfExists('business_hours');
    }
};

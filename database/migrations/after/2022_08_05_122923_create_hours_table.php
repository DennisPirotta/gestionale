<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('hours', static function (Blueprint $table) {
            $table->id();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('hour_type_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hours');
    }
};

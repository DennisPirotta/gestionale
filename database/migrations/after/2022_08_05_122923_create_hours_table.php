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


            // per commesse
            $table  ->foreignId('order')
                    ->nullable()
                    ->constrained('orders')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            // per foglio intervento
            $table->foreignId('report')
                ->nullable()
                ->constrained('technical_reports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();


            // per singoli campi di descrizione (nome corso / tipo lavoro ufficio / altro)
            $table->string('description')->nullable();

            // per ferie
            $table->foreignId('holiday')
                ->nullable()
                ->constrained('holidays')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();


            // per ferie
            $table->foreignId('user')
                ->nullable() // da rimuovere !!!!
                ->constrained('users')
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

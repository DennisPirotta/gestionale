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
            $table  ->foreignId('hour_type')
                    ->constrained('hour_types')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();


            // per commesse
            $table->year('year')->nullable();
            $table  ->foreignId('order')
                    ->nullable()
                    ->constrained('orders')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            $table  ->foreignId('job_type')
                    ->nullable()
                    ->constrained('job_types')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            // per foglio intervento
            $table->foreignId('customer')
                ->nullable()
                ->constrained('customers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('customer2')
                ->nullable()
                ->constrained('customers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // per singoli campi di descrizione ( nome corso / tipo lavoro ufficio / altro )
            $table->string('description')->nullable();





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

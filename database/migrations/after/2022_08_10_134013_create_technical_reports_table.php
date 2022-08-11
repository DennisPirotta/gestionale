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
    public function up(): void
    {
        Schema::create('technical_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table  ->foreignId('customer')
                    ->constrained('customers')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            $table  ->foreignId('customer2')
                    ->constrained('customers')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            $table  ->foreignId('order')
                    ->nullable()
                    ->constrained('orders')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

            $table->integer('number');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_reports');
    }
};

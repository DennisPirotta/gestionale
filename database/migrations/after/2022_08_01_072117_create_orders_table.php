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
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('innerCode');
            $table->bigInteger('outerCode');
            $table->string('description');
            $table->integer('hourSW');
            $table->integer('hourMS')->default(0);
            $table->integer('hourFAT')->default(0);
            $table->integer('hourSAF')->default(0);
            $table->string('progress');
            $table->date('opening');
            $table->date('closing');
            $table->timestamps();

            $table->foreignId('customer')
                ->constrained('customers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('manager')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('company')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('country')
                ->constrained('countries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('status')
                ->constrained('statuses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

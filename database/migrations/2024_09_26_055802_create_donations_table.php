<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('donator');
            $table->morphs('campaign');
            $table->decimal('running_amount')
                ->comment('Total amount of the donation at the time a record is inserted');
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->foreignId('transaction_id')->index();
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};

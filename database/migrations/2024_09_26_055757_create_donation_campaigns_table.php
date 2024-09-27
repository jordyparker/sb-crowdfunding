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
        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('creator');
            $table->morphs('receiver');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->decimal('target_amount');
            $table->decimal('min_donation_amount')->nullable();
            $table->integer('number_of_participants')->nullable();
            $table->string('currency')->default('XAF');
            $table->foreignId('category_id')->nullable()->index();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_campaigns');
    }
};

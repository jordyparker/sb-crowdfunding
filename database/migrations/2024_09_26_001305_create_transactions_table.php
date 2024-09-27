<?php

use App\Enums\CommonStatus;
use App\Enums\PaymentMethod;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id')->unique();
            $table->nullableMorphs('payer'); // Payer is nullable because some transactions can be done anonymously
            $table->morphs('item');
            $table->string('payment_number')
                ->comment('Phone number or credit card number used for the payment');
            $table->decimal('amount');
            $table->decimal('base_amount')
                ->comment('converted to base amount');
            $table->string('currency')->default('XAF');
            $table->string('base_currency')->default('XAF');
            $table->string('external_id')->nullable()
                ->comment('transaction reference from external sources');
            $table->enum('payment_method', array_column(PaymentMethod::cases(), 'value'))
                ->comment("method of payment");
            $table->enum('status', array_column(CommonStatus::cases(), 'value'))
                ->default(CommonStatus::PENDING);
            $table->json('details')->nullable()->comment('any details about transactions: external ids..., payment number etc...');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

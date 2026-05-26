<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('loan')->index();
            $table->decimal('amount', 12, 3);
            $table->decimal('monthly_installment', 12, 3);
            $table->decimal('paid_amount', 12, 3)->default(0);
            $table->date('starts_on');
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

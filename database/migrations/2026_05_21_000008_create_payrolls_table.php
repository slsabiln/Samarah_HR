<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->decimal('basic_salary', 12, 3)->default(0);
            $table->decimal('allowances', 12, 3)->default(0);
            $table->decimal('overtime_amount', 12, 3)->default(0);
            $table->decimal('leave_deduction', 12, 3)->default(0);
            $table->decimal('penalty_deduction', 12, 3)->default(0);
            $table->decimal('loan_deduction', 12, 3)->default(0);
            $table->decimal('gross_salary', 12, 3)->default(0);
            $table->decimal('net_salary', 12, 3)->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'period_month', 'period_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};

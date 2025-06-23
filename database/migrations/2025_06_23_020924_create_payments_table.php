<?php

use App\Models\Customer;
use App\Models\Semester;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Semester::class)->constrained()->cascadeOnDelete();
            $table->string('document_number')->unique()->index();
            $table->date('payment_date');
            $table->string('payment_method')->nullable();
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('note')->nullable();
            $table->userAuditable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

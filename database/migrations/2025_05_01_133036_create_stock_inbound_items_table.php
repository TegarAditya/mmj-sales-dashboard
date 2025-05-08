<?php

use App\Models\Product;
use App\Models\StockInbound;
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
        Schema::create('stock_inbound_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StockInbound::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('cost')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->userAuditable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_inbound_items');
    }
};

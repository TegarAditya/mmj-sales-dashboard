<?php

use App\Models\Curriculum;
use App\Models\EducationalClass;
use App\Models\EducationalLevel;
use App\Models\EducationalSubject;
use App\Models\Publisher;
use App\Models\Semester;
use App\Models\Supplier;
use App\Models\Type;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Type::class)->constrained();
            $table->foreignIdFor(Supplier::class)->constrained();
            $table->foreignIdFor(Publisher::class)->constrained();
            $table->foreignIdFor(Semester::class)->constrained();
            $table->foreignIdFor(Curriculum::class)->constrained();
            $table->foreignIdFor(EducationalLevel::class)->constrained();
            $table->foreignIdFor(EducationalClass::class)->constrained();
            $table->foreignIdFor(EducationalSubject::class)->constrained();
            $table->string('code')->unique();
            $table->string('name')->index();
            $table->integer('page_count')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('price')->default(0);
            $table->integer('cost')->default(0);
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
        Schema::dropIfExists('products');
    }
};

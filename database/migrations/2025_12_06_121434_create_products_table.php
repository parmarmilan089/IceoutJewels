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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->enum('gender', ['men', 'women', 'unisex'])->default('unisex');
            $table->string('material');
            $table->decimal('base_price', 10, 2);
            $table->decimal('weight', 8, 2)->comment('in grams');
            $table->string('featured_image')->nullable();
            $table->string('video_url')->nullable();
            
            // Duty & Shipping fields
            $table->decimal('duty_fee', 8, 2)->default(0);
            $table->decimal('customs_fee', 8, 2)->default(0);
            $table->decimal('insurance_fee', 8, 2)->default(0);
            $table->decimal('shipping_fee', 8, 2)->default(0);
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            // Status fields
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('sku');
            $table->index('slug');
            $table->index('category_id');
            $table->index('gender');
            $table->index('status');
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

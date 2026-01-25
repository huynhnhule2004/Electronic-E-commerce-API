<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->integer('_lft')->default(0)->index();
            $table->integer('_rgt')->default(0)->index();
            $table->unsignedInteger('depth')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['parent_id', '_lft', '_rgt']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

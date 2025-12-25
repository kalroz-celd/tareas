<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();

            $table->enum('status', ['planning','active','on_hold','completed','cancelled'])
                ->default('planning');

            $table->enum('priority', ['low','medium','high','urgent'])
                ->default('medium');

            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();

            $table->boolean('is_archived')->default(false);

            $table->timestamps();

            $table->index(['status', 'priority', 'is_archived']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'client_id')) {
                $table->foreignId('client_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete()
                    ->after('id');
            }

            if (!Schema::hasColumn('projects', 'payment_due_date')) {
                $table->date('payment_due_date')->nullable()->after('client_id');
            }

            if (!Schema::hasColumn('projects', 'amount')) {
                $table->decimal('amount', 12, 2)->nullable()->after('payment_due_date');
            }

            if (!Schema::hasColumn('projects', 'currency')) {
                $table->string('currency', 3)->nullable()->after('amount'); // nullable, por si quieres no usarlo
            }

            if (!Schema::hasColumn('projects', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'overdue'])
                    ->nullable()
                    ->after('currency');
            }

            if (!Schema::hasColumn('projects', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }

            // Índice opcional útil
            $table->index(['client_id', 'payment_due_date'], 'projects_client_due_idx');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop index si existe
            try { $table->dropIndex('projects_client_due_idx'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('projects', 'client_id')) {
                try { $table->dropConstrainedForeignId('client_id'); } catch (\Throwable $e) {
                    // fallback si el nombre de FK difiere
                    try { $table->dropForeign(['client_id']); } catch (\Throwable $e2) {}
                    try { $table->dropColumn('client_id'); } catch (\Throwable $e3) {}
                }
            }

            foreach (['payment_due_date','amount','currency','payment_status','paid_at'] as $col) {
                if (Schema::hasColumn('projects', $col)) {
                    try { $table->dropColumn($col); } catch (\Throwable $e) {}
                }
            }
        });
    }
};

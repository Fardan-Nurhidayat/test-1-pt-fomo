<?php

use App\Models\User;
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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('action_type', [
                'inventory_adjustment',
                'order_created',
                'order_cancelled',
                'order_paid',
                'flash_sale_created',
                'flash_sale_activated',
                'product_created',
                'product_updated',
            ]);
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index('action_type');
            $table->index('user_id');
            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

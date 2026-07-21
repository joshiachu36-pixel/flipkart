<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Resolve existing duplicate priorities per product safely before applying unique constraint
        $productsWithDuplicates = DB::table('product_variants')
            ->select('product_id', 'priority', DB::raw('COUNT(*) as total'))
            ->groupBy('product_id', 'priority')
            ->having('total', '>', 1)
            ->pluck('product_id');

        foreach ($productsWithDuplicates as $productId) {
            $variants = DB::table('product_variants')
                ->where('product_id', $productId)
                ->orderBy('id', 'asc')
                ->get();

            $seqPriority = 1;
            foreach ($variants as $variant) {
                DB::table('product_variants')
                    ->where('id', $variant->id)
                    ->update(['priority' => $seqPriority]);
                $seqPriority++;
            }
        }

        // 2. Add composite unique constraint on (product_id, priority)
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unique(['product_id', 'priority'], 'product_variants_product_id_priority_unique');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique('product_variants_product_id_priority_unique');
        });
    }
};

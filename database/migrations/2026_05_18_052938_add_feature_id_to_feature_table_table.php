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
        Schema::table('feature_table', function (Blueprint $table) {
            $table->foreignId('feature_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feature_table', function (Blueprint $table) {
            $table->dropForeign(['feature_id']);
            $table->dropColumn('feature_id');
        });
    }
};

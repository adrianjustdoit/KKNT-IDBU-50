<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('waste_composition')->nullable()->after('tokopedia_link');
            $table->string('model_type')->nullable()->after('waste_composition'); // kompos, terrazzo, keychain
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['waste_composition', 'model_type']);
        });
    }
};

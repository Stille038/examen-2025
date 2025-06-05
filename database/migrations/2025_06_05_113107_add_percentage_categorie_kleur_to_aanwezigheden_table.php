<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('aanwezigheden', function (Blueprint $table) {
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('categorie')->nullable();
            $table->string('kleur')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('aanwezigheden', function (Blueprint $table) {
            $table->dropColumn(['percentage', 'categorie', 'kleur']);
        });
    }
};


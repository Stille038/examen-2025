<?php
// maakt kolommen aan in mysql
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('aanwezigheden', function (Blueprint $table) {
            $table->id();
            $table->string('studentnummer');
            $table->integer('aanwezigheid');
            $table->integer('rooster');
            $table->integer('week');
            $table->integer('jaar');
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aanwezigheden');
    }
};
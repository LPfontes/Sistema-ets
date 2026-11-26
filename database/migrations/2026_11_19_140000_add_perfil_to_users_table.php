<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Padrão visitante: nenhuma conta existente ganha privilégio de ADM automaticamente.
        Schema::table('users', fn (Blueprint $table) => $table->string('perfil', 20)->default('visitante'));
    }
    public function down(): void {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('perfil'));
    }
};

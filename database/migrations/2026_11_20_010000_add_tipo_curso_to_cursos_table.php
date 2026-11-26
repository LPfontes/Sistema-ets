<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Valor padrão preserva todos os cursos antigos.
        Schema::table('cursos', function (Blueprint $table) {
            $table->string('tipo_curso', 30)->default('Presencial');
        });
    }
    public function down(): void {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn('tipo_curso');
        });
    }
};

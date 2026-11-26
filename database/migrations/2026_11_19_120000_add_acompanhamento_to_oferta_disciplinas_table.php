<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('oferta_disciplinas', function (Blueprint $table) {
            // Vínculo opcional: ofertas antigas permanecem sem associação.
            $table->foreignId('oferta_curso_id')->nullable()->constrained('oferta_cursos')->restrictOnDelete();
            // Horas realizadas: nunca presumir que horas previstas foram cumpridas.
            $table->unsignedInteger('carga_horaria_cumprida')->default(0);
        });
    }
    public function down(): void
    {
        Schema::table('oferta_disciplinas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('oferta_curso_id');
            $table->dropColumn('carga_horaria_cumprida');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('oferta_disciplinas', function (Blueprint $table) {
            $table->unsignedInteger('horas_legadas')->default(0);
        });
        // Preserva horas já informadas manualmente antes do módulo de aulas.
        DB::table('oferta_disciplinas')->update(['horas_legadas' => DB::raw('carga_horaria_cumprida')]);
    }
    public function down(): void
    {
        Schema::table('oferta_disciplinas', fn (Blueprint $table) => $table->dropColumn('horas_legadas'));
    }
};

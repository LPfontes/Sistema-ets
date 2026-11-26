<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('acessos_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome_usuario');
            $table->string('email_usuario');
            $table->string('perfil', 20);
            $table->string('evento', 20); // login ou logout
            $table->ipAddress('ip')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['perfil', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('acessos_usuarios'); }
};

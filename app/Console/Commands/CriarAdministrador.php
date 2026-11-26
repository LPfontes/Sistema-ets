<?php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/** Cria a primeira conta ADM sem senha fixa ou credenciais no código. */
class CriarAdministrador extends Command
{
    protected $signature = 'ets:criar-admin';
    protected $description = 'Cria ou promove uma conta para administrador do Sistema ETS';

    public function handle(): int
    {
        $nome = trim((string) $this->ask('Nome do administrador'));
        $email = trim((string) $this->ask('E-mail do administrador'));
        $senha = (string) $this->secret('Senha (mínimo 8 caracteres)');
        $confirmacao = (string) $this->secret('Confirme a senha');
        if (! $nome || ! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8 || $senha !== $confirmacao) {
            $this->error('Dados inválidos ou senhas diferentes. Nenhuma alteração foi realizada.');
            return self::FAILURE;
        }
        $usuario = User::where('email', $email)->first();
        if ($usuario && ! $this->confirm('A conta já existe. Promover para administrador e alterar a senha?', false)) return self::FAILURE;
        if ($usuario) $usuario->update(['name' => $nome, 'password' => Hash::make($senha), 'perfil' => 'admin']);
        else User::create(['name' => $nome, 'email' => $email, 'password' => Hash::make($senha), 'perfil' => 'admin']);
        $this->info('Administrador configurado com sucesso.');
        return self::SUCCESS;
    }
}

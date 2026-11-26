<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/** Gerencia visitantes. Senhas são geradas no servidor e mostradas uma única vez. */
class VisitanteController extends Controller
{
    public function index()
    {
        return view('visitantes.index', [
            'visitantes' => User::where('perfil', 'visitante')->orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('visitantes.form', ['visitante' => new User()]);
    }

    public function store(Request $request)
    {
        // O ADM informa apenas nome e e-mail. A senha é criada aleatoriamente.
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
        ]);
        $senha = Str::password(16);
        User::create([...$dados, 'password' => $senha, 'perfil' => 'visitante']);

        // A senha é exibida uma vez após o cadastro; não é salva em texto puro.
        return redirect()->route('visitantes.index')->with([
            'success' => 'Visitante cadastrado com sucesso.',
            'senha_gerada' => $senha,
            'email_senha' => $dados['email'],
        ]);
    }

    public function edit(User $visitante)
    {
        abort_unless($visitante->perfil === 'visitante', 404);
        return view('visitantes.form', compact('visitante'));
    }

    public function update(Request $request, User $visitante)
    {
        abort_unless($visitante->perfil === 'visitante', 404);
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($visitante->id)],
        ]);
        $visitante->update($dados);
        return redirect()->route('visitantes.index')->with('success', 'Visitante atualizado.');
    }

    public function regenerarSenha(User $visitante)
    {
        abort_unless($visitante->perfil === 'visitante', 404);
        // A redefinição invalida a senha antiga. Entregue a nova senha com segurança.
        $senha = Str::password(16);
        $visitante->update(['password' => $senha]);
        return redirect()->route('visitantes.index')->with([
            'success' => 'Nova senha gerada. A senha anterior não funcionará mais.',
            'senha_gerada' => $senha,
            'email_senha' => $visitante->email,
        ]);
    }

    public function destroy(User $visitante)
    {
        abort_unless($visitante->perfil === 'visitante', 404);
        $visitante->delete();
        return redirect()->route('visitantes.index')->with('success', 'Visitante excluído.');
    }
}

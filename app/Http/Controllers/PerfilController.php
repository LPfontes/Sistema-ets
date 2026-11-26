<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

/** Perfil do administrador: senha atual obrigatória para trocar a senha. */
class PerfilController extends Controller
{
    public function edit(Request $request)
    {
        return view('perfil.edit', ['usuario' => $request->user()]);
    }

    public function senha(Request $request)
    {
        $dados = $request->validate([
            'senha_atual' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
        ]);
        $request->user()->update(['password' => $dados['password']]);
        // Encerra outras sessões? Não automaticamente: apenas troca a senha armazenada.
        return back()->with('success', 'Senha do administrador alterada com sucesso.');
    }

    public function foto(Request $request)
    {
        $dados = $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
        $usuario = $request->user();
        $antiga = $usuario->foto_perfil;
        $caminho = $dados['foto']->store('fotos-perfil', 'public');
        $usuario->update(['foto_perfil' => $caminho]);
        if ($antiga) Storage::disk('public')->delete($antiga);
        return back()->with('success', 'Foto do perfil atualizada.');
    }
}

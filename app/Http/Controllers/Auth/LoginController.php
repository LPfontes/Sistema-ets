<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/** US6: autenticação com sessão, proteção CSRF e renovação de sessão. */
class LoginController extends Controller
{
    public function create() { return view('auth.login'); }

    public function store(Request $request)
    {
        $dados = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt($dados)) {
            throw ValidationException::withMessages(['email' => 'E-mail ou senha inválidos.']);
        }
        $request->session()->regenerate();
        $this->registrar($request, 'login');
        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request)
    {
        $this->registrar($request, 'logout');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function registrar(Request $request, string $evento): void
    {
        $usuario = $request->user();
        if (! $usuario) return;
        DB::table('acessos_usuarios')->insert([
            'user_id' => $usuario->id,
            'nome_usuario' => $usuario->name,
            'email_usuario' => $usuario->email,
            'perfil' => $usuario->perfil,
            'evento' => $evento,
            'ip' => $request->ip(),
            'created_at' => now(),
        ]);
    }
}

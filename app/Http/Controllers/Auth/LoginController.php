<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/** US6: autenticação com sessão, proteção CSRF e renovação de sessão. */
class LoginController extends Controller
{
    public function create() { return view('auth.login'); }

    public function store(Request $request)
    {
        $dados = $request->validate(['email' => 'required|string', 'password' => 'required|string']);
        $identificador = strtolower(trim($dados['email']));
        $adminName = trim((string) env('ADMIN_NAME', 'Administrador do Sistema'));
        $adminEmail = strtolower(trim((string) env('ADMIN_EMAIL', 'admin@spd.local')));
        $adminUser = strtolower(trim((string) env('ADMIN_USER', 'admin')));
        $adminPassword = (string) env('ADMIN_PASSWORD', 'admin123');

        if ($identificador === $adminUser || $identificador === 'admin') {
            $identificador = $adminEmail;
        }

        // Garante que o usuário administrador unificado exista e esteja sincronizado
        if ($identificador === $adminEmail && $dados['password'] === $adminPassword) {
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName,
                    'password' => $adminPassword,
                    'perfil' => 'admin',
                ]
            );
        }

        if (! Auth::attempt(['email' => $identificador, 'password' => $dados['password']])) {
            throw ValidationException::withMessages(['email' => 'E-mail/usuário ou senha inválidos.']);
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

    /**
     * Autenticação Single Sign-On (SSO) via token assinado pelo Portal Gateway Unificado.
     */
    public function sso(Request $request)
    {
        $token = (string) $request->query('token', '');
        $segredo = env(
            'SSO_SECRET',
            env('JWT_SECRET', 'dda44fc86d93b0f196a4ad1612a17e9cfd451e3f29f9e25ad3bec3fab02030df82765460ededd62e84ff65d54f179d94')
        );

        $partes = explode('.', $token);
        if (count($partes) !== 3) {
            return redirect()->route('login')->withErrors(['email' => 'Token SSO inválido.']);
        }

        [$headerB64, $corpoB64, $assinaturaB64] = $partes;
        $esperadaRaw = hash_hmac('sha256', "{$headerB64}.{$corpoB64}", $segredo, true);
        $esperadaB64 = rtrim(strtr(base64_encode($esperadaRaw), '+/', '-_'), '=');

        if (! hash_equals($esperadaB64, $assinaturaB64)) {
            return redirect()->route('login')->withErrors(['email' => 'Assinatura SSO inválida.']);
        }

        $payloadJson = base64_decode(strtr($corpoB64, '-_', '+/'), true);
        $payload = is_string($payloadJson) ? json_decode($payloadJson, true) : null;

        if (! is_array($payload) || (isset($payload['exp']) && time() > (int) $payload['exp'])) {
            return redirect()->route('login')->withErrors(['email' => 'Sessão SSO expirada. Faça login novamente no Gateway.']);
        }

        $isAdmin = ($payload['role'] ?? '') === 'ADMIN' || ($payload['perfil'] ?? '') === 'admin';
        $email = strtolower(trim((string) ($payload['email'] ?? '')));
        if (! $email || ! str_contains($email, '@')) {
            $ident = strtolower(trim((string) ($payload['usuario'] ?? $payload['siape'] ?? 'usuario')));
            $email = "{$ident}@ufpb.br";
        }
        $nome = trim((string) ($payload['nome'] ?? 'Usuário SSO'));

        $usuario = User::where('email', $email)->first();
        if (! $usuario && $isAdmin) {
            $usuario = User::where('perfil', 'admin')->first();
        }
        if (! $usuario) {
            $usuario = User::create([
                'name' => $nome,
                'email' => $email,
                'password' => Str::password(24),
                'perfil' => $isAdmin ? 'admin' : 'visitante',
            ]);
        }

        Auth::login($usuario);
        $request->session()->regenerate();
        $this->registrar($request, 'login');

        return redirect()->route('dashboard');
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

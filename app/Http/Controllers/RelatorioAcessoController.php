<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** US7: histórico de login e logout dos visitantes, visível apenas ao ADM. */
class RelatorioAcessoController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->validate(['email' => 'nullable|string|max:255', 'evento' => 'nullable|in:login,logout']);
        $consulta = DB::table('acessos_usuarios')->where('perfil', 'visitante');
        if (! empty($filtro['email'])) $consulta->where('email_usuario', 'like', '%'.$filtro['email'].'%');
        if (! empty($filtro['evento'])) $consulta->where('evento', $filtro['evento']);
        return view('relatorios.acessos', ['acessos' => $consulta->orderByDesc('created_at')->paginate(20)->withQueryString()]);
    }
}

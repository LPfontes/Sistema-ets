<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** US6: impede visitantes de acessar páginas e ações administrativas. */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->perfil === 'admin', 403, 'Acesso restrito ao administrador.');
        return $next($request);
    }
}

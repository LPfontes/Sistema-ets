@extends('layouts.app')
@section('title', 'Relatório de Acesso dos Visitantes')
@section('content')
<h2>Relatório de Acesso dos Visitantes</h2><p class="text-muted">Histórico de login e logout. Registros começam após a ativação do módulo.</p>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="email" class="form-control" placeholder="Filtrar por e-mail" value="{{ request('email') }}"></div><div class="col-md-3"><select name="evento" class="form-select"><option value="">Todos os eventos</option><option value="login" @selected(request('evento')==='login')>Login</option><option value="logout" @selected(request('evento')==='logout')>Logout</option></select></div><div class="col-md-2"><button class="btn btn-primary">Filtrar</button></div></form>
<div class="table-responsive"><table class="table table-striped bg-white"><thead><tr><th>Visitante</th><th>E-mail</th><th>Data e hora</th><th>Evento</th><th>IP</th></tr></thead><tbody>
@forelse($acessos as $acesso)<tr><td>{{ $acesso->nome_usuario }}</td><td>{{ $acesso->email_usuario }}</td><td>{{ \Carbon\Carbon::parse($acesso->created_at)->format('d/m/Y H:i:s') }}</td><td>{{ ucfirst($acesso->evento) }}</td><td>{{ $acesso->ip ?? '-' }}</td></tr>
@empty<tr><td colspan="5">Nenhum acesso registrado.</td></tr>@endforelse
</tbody></table></div>{{ $acessos->links() }}
@endsection

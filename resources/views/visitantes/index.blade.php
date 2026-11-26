{{-- A senha gerada é exibida apenas na resposta imediatamente após o cadastro/redefinição. --}}
@extends('layouts.app')
@section('title', 'Visitantes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h2>Visitantes</h2><a href="{{ route('visitantes.create') }}" class="btn btn-primary">Cadastrar visitante</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('senha_gerada'))
<div class="alert alert-warning" role="alert">
  <strong>Senha gerada para {{ session('email_senha') }}</strong>
  <p class="mb-2">Copie agora e entregue ao visitante por um canal seguro. A senha não será exibida novamente após sair desta página.</p>
  <div class="input-group"><input type="text" id="senhaGerada" class="form-control" value="{{ session('senha_gerada') }}" readonly aria-label="Senha gerada"><button type="button" class="btn btn-outline-dark" onclick="navigator.clipboard.writeText(document.getElementById('senhaGerada').value).then(()=>alert('Senha copiada.'))">Copiar senha</button></div>
</div>
@endif
<div class="table-responsive"><table class="table table-striped bg-white"><thead><tr><th>Nome</th><th>E-mail</th><th>Ações</th></tr></thead><tbody>
@forelse($visitantes as $visitante)
<tr><td>{{ $visitante->name }}</td><td>{{ $visitante->email }}</td><td><div class="d-flex flex-wrap gap-2">
<a class="btn btn-sm btn-warning" href="{{ route('visitantes.edit', $visitante) }}">Editar</a>
<form method="POST" action="{{ route('visitantes.regenerar-senha', $visitante) }}" onsubmit="return confirm('Gerar nova senha? A senha anterior deixará de funcionar.')">@csrf<button class="btn btn-sm btn-outline-primary">Gerar nova senha</button></form>
<form method="POST" action="{{ route('visitantes.destroy', $visitante) }}" onsubmit="return confirm('Excluir este visitante?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Excluir</button></form>
</div></td></tr>
@empty<tr><td colspan="3">Nenhum visitante cadastrado.</td></tr>@endforelse
</tbody></table></div>{{ $visitantes->links() }}
@endsection

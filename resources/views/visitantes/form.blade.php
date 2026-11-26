{{-- Cadastro e edição de visitantes: senha sempre gerada pelo sistema. --}}
@extends('layouts.app')
@section('title', $visitante->exists ? 'Editar visitante' : 'Cadastrar visitante')
@section('content')
<h2>{{ $visitante->exists ? 'Editar visitante' : 'Cadastrar visitante' }}</h2>
@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul></div>
@endif
<form method="POST" action="{{ $visitante->exists ? route('visitantes.update', $visitante) : route('visitantes.store') }}" class="card card-body">
@csrf
@if($visitante->exists) @method('PUT') @endif
<div class="mb-3"><label class="form-label" for="nome_visitante">Nome *</label><input id="nome_visitante" class="form-control" name="name" required maxlength="255" value="{{ old('name', $visitante->name) }}"></div>
<div class="mb-3"><label class="form-label" for="email_visitante">E-mail *</label><input id="email_visitante" type="email" class="form-control" name="email" required maxlength="255" value="{{ old('email', $visitante->email) }}"></div>
<div class="alert alert-info">{{ $visitante->exists ? 'Para trocar a senha, use o botão Gerar nova senha na lista de visitantes.' : 'Uma senha segura será gerada automaticamente ao salvar. Copie-a na tela seguinte, pois ela aparecerá somente uma vez.' }}</div>
<div class="d-flex gap-2"><button class="btn btn-primary">Salvar</button><a class="btn btn-secondary" href="{{ route('visitantes.index') }}">Cancelar</a></div>
</form>
@endsection

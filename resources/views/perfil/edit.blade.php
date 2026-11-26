@extends('layouts.app')
@section('title', 'Meu Perfil')
@section('content')
<h2>Meu Perfil — Administrador</h2>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul></div>@endif
<div class="row g-4">
<div class="col-lg-5"><div class="card card-body h-100">
<h5>Foto do perfil</h5>
@if($usuario->foto_perfil)<img src="{{ asset('storage/'.$usuario->foto_perfil) }}" alt="Foto do administrador" class="rounded-circle object-fit-cover mb-3" style="width:120px;height:120px">@else<p class="text-muted">Nenhuma foto cadastrada.</p>@endif
<p><strong>Nome:</strong> {{ $usuario->name }}<br><strong>E-mail:</strong> {{ $usuario->email }}</p>
<form method="POST" action="{{ route('perfil.foto') }}" enctype="multipart/form-data">@csrf
<label class="form-label" for="foto">Escolher foto (JPG, PNG ou WebP; até 2 MB)</label>
<input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="form-control mb-3" required>
<button class="btn btn-primary">Salvar foto</button></form>
</div></div>
<div class="col-lg-7"><div class="card card-body">
<h5>Alterar minha senha</h5>
<form method="POST" action="{{ route('perfil.senha') }}">@csrf @method('PUT')
<div class="mb-3"><label for="senha_atual" class="form-label">Senha atual *</label><input id="senha_atual" type="password" name="senha_atual" class="form-control" autocomplete="current-password" required></div>
<div class="mb-3"><label for="password" class="form-label">Nova senha *</label><input id="password" type="password" name="password" class="form-control" minlength="10" autocomplete="new-password" required><small class="text-muted">Mínimo de 10 caracteres, com letras e números.</small></div>
<div class="mb-3"><label for="password_confirmation" class="form-label">Confirmar nova senha *</label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" minlength="10" autocomplete="new-password" required></div>
<button class="btn btn-primary">Alterar senha</button></form>
</div></div>
</div>
@endsection

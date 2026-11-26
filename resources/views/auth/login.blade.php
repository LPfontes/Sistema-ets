<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login — Sistema ETS/UFPB</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><main class="container" style="max-width:450px;margin-top:9vh">
<div class="card shadow-sm"><div class="card-body p-4"><h3 class="mb-2">Sistema ETS/UFPB</h3><p class="text-muted">Entre para acessar o painel.</p>
@if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
<form method="POST" action="{{ route('login') }}">@csrf
<div class="mb-3"><label for="email" class="form-label">E-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username"></div>
<div class="mb-3"><label for="password" class="form-label">Senha</label><input id="password" type="password" name="password" class="form-control" required autocomplete="current-password"></div>
<button class="btn btn-primary w-100" type="submit">Entrar</button></form>
<p class="small text-muted mt-3 mb-0">O cadastro de visitantes é realizado pelo administrador.</p></div></div></main></body></html>

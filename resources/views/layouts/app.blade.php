<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Título exibido na aba do navegador -->
<title>
    @yield('title', 'Análise de Cargas Horárias de Cursos - ETS/UFPB')
</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            background: #1d426b;
            min-height: calc(100vh - 56px);
        }

        .sidebar a {
            display: block;
            padding: 12px 18px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #32628f;
        }

        /* O menu mantém altura da janela e rolagem independente do conteúdo. */
        .ets-sidebar { position: sticky; top: 0; height: calc(100vh - 56px); overflow-y: auto; }
        .ets-group summary { color: #fff; cursor: pointer; padding: 13px 18px; font-weight: 600; list-style-position: inside; }
        .ets-group summary:hover { background: #32628f; }
        .ets-group a { padding-left: 32px; font-size: .93rem; }
        .ets-group[open] { background: rgba(0,0,0,.10); }
        @media(max-width:767px) { .ets-sidebar { position: static; height: auto; max-height: 55vh; } }
        .card-dashboard {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        /* Visitantes consultam dados; rotas administrativas são bloqueadas no servidor. */
        @auth
        @if(auth()->user()->perfil === 'visitante')
        main a[href$="/create"], main a[href*="/edit"],
        main form[method="POST"] { display: none !important; }
        @endif
        @endauth

        /* Impressão: mostrar somente o conteúdo, sem menus ou botões. */
        @media print {
            @page { size: A4; margin: 12mm; }
            html, body { background: #fff !important; color: #111 !important; }
            nav.navbar, aside.sidebar, .ets-print-toolbar,
            .btn, button, .pagination, nav[aria-label="pagination"],
            .no-print { display: none !important; }
            .container-fluid, .container-fluid > .row, main {
                display: block !important; width: 100% !important;
                max-width: none !important; margin: 0 !important;
                padding: 0 !important; flex: none !important;
            }
            main { font-size: 10pt; }
            main a { color: #111 !important; text-decoration: none !important; }
            main table { width: 100% !important; border-collapse: collapse !important; }
            main table thead { display: table-header-group; }
            main table tr { break-inside: avoid; page-break-inside: avoid; }
            main table th, main table td { border-bottom: 1px solid #ddd !important; }
            main .card, main .table-responsive { box-shadow: none !important; overflow: visible !important; }
            /* Oculta somente a coluna de ações, preservando as demais colunas. */
            main table .ets-print-action-column { display: none !important; }
            /* Links de retorno ao seletor de curso não pertencem ao PDF. */
            main a[href*="/extratos/curso"] { display: none !important; }
            .ets-print-header { display: block !important; margin-bottom: 14px; border-bottom: 2px solid #1d426b; padding-bottom: 8px; }
        }
        @media screen { .ets-print-header { display: none; } }
    </style>
</head>

<body>


<!-- Barra de navegação superior do sistema -->
<nav class="navbar navbar-dark px-4"
     style="background:#12345a;">

    <!-- Nome do sistema e link para o Dashboard -->
    <a href="{{ route('dashboard') }}"
       class="navbar-brand">
        Análise de Cargas Horárias de Cursos - ETS/UFPB
    </a>

    @auth
    <div class="ms-auto d-flex align-items-center gap-3 text-white">
        @if(auth()->user()->foto_perfil)
        <img src="{{ asset('storage/'.auth()->user()->foto_perfil) }}" alt="Foto do perfil" class="rounded-circle object-fit-cover" style="width:36px;height:36px">
        @endif
        <span class="small">{{ auth()->user()->name }} ({{ auth()->user()->perfil === 'admin' ? 'ADM' : 'Visitante' }})</span>
        <form method="POST" action="{{ route('logout') }}" class="m-0">@csrf<button class="btn btn-sm btn-outline-light">Sair</button></form>
    </div>
    @endauth
</nav>


<div class="container-fluid">
    <div class="row">

        <!-- MENU LATERAL: grupos recolhíveis nativos do HTML, sem bibliotecas extras. -->
        <aside class="col-md-3 col-lg-2 sidebar p-0 ets-sidebar">
            <div class="px-3 pt-3 pb-2 text-white fw-semibold">Navegação</div>
            @if(auth()->user()->perfil === 'visitante')
                <div class="alert alert-info m-2 p-2 small">Modo consulta: alterações restritas ao ADM.</div>
            @endif
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

            <details class="ets-group" @if(request()->routeIs('cursos.*', 'modulos.*', 'semestres.*', 'disciplinas.*', 'docentes.*')) open @endif>
                <summary>Gestão Acadêmica</summary>
                <a href="{{ route('cursos.index') }}" class="{{ request()->routeIs('cursos.*') ? 'active' : '' }}">Cursos</a>
                <a href="{{ route('modulos.index') }}" class="{{ request()->routeIs('modulos.*') ? 'active' : '' }}">Módulos</a>
                <a href="{{ route('semestres.index') }}" class="{{ request()->routeIs('semestres.*') ? 'active' : '' }}">Semestres</a>
                <a href="{{ route('disciplinas.index') }}" class="{{ request()->routeIs('disciplinas.*') ? 'active' : '' }}">Disciplinas</a>
                <a href="{{ route('docentes.index') }}" class="{{ request()->routeIs('docentes.*') ? 'active' : '' }}">Docentes</a>
            </details>

            <details class="ets-group" @if(request()->routeIs('ofertas-cursos.*', 'ofertas-disciplinas.*', 'registros-aulas.*')) open @endif>
                <summary>Ofertas e Aulas</summary>
                <a href="{{ route('ofertas-cursos.index') }}" class="{{ request()->routeIs('ofertas-cursos.*') ? 'active' : '' }}">Ofertas de Cursos</a>
                <a href="{{ route('ofertas-disciplinas.index') }}" class="{{ request()->routeIs('ofertas-disciplinas.*') ? 'active' : '' }}">Ofertas de Disciplinas</a>
                <a href="{{ route('registros-aulas.index') }}" class="{{ request()->routeIs('registros-aulas.*') ? 'active' : '' }}">Registro de Aulas e Carga Horária</a>
            </details>

            <details class="ets-group" @if(request()->routeIs('extratos.*')) open @endif>
                <summary>Extratos de Carga Horária</summary>
                <a href="{{ route('extratos.curso') }}">Por Curso</a>
                <a href="{{ route('extratos.semestre') }}">Por Semestre</a>
                <a href="{{ route('extratos.docente') }}">Por Docente</a>
            </details>

            @if(auth()->user()->perfil === 'admin')
            <details class="ets-group" @if(request()->routeIs('visitantes.*', 'relatorios.acessos')) open @endif>
                <summary>Administração</summary>
                <a href="{{ route('visitantes.index') }}">Visitantes</a>
                <a href="{{ route('relatorios.acessos') }}">Relatório de Acesso dos Visitantes</a>
            </details>
            @endif

            <details class="ets-group" @if(request()->routeIs('perfil.*', 'ajuda')) open @endif>
                <summary>Conta e Ajuda</summary>
                @if(auth()->user()->perfil === 'admin')<a href="{{ route('perfil.edit') }}">Meu Perfil</a>@endif
                <a href="{{ route('ajuda') }}">Manual do Usuário</a>
            </details>
        </aside>

        <!-- CONTEÚDO DA PÁGINA -->
        <main class="col-md-9 col-lg-10 p-4">
            {{-- Exibe impressão somente nas listagens e nos extratos; não interfere nos formulários. --}}
            @php
                $etsPodeImprimir = request()->routeIs(
                    'cursos.index', 'modulos.index', 'semestres.index',
                    'disciplinas.index', 'docentes.index',
                    'ofertas-cursos.index', 'ofertas-disciplinas.index',
                    'registros-aulas.index', 'visitantes.index',
                    'relatorios.acessos', 'extratos.*',
                    'cursos.analise-carga-horaria',
                    'cursos.show', 'modulos.show', 'semestres.show',
                    'disciplinas.show', 'docentes.show',
                    'ofertas-cursos.show', 'ofertas-disciplinas.show'
                );
            @endphp
            @if($etsPodeImprimir)
                <div class="ets-print-toolbar d-flex justify-content-end mb-3 gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        Imprimir / Salvar em PDF
                    </button>
                </div>
                <div class="ets-print-header">
                    <strong>Análise de Cargas Horárias de Cursos — ETS/UFPB</strong>
                    <div class="small">Emissão: {{ now()->format('d/m/Y H:i') }}</div>
                </div>
            @endif
            @yield('content')
        </main>

    </div>
</div>

<script>
/*
 * Marca a coluna “Ações” das tabelas somente para impressão.
 * Detecta o cabeçalho pelo texto, sem depender da posição da coluna.
 * Não remove células da tela e não altera os dados do sistema.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('main table').forEach(function (tabela) {
        const cabecalhos = Array.from(tabela.querySelectorAll('thead tr:first-child th'));
        cabecalhos.forEach(function (th, indice) {
            const titulo = (th.textContent || '').trim().toLocaleLowerCase('pt-BR');
            if (titulo === 'ações' || titulo === 'acao' || titulo === 'ações / opções') {
                th.classList.add('ets-print-action-column');
                tabela.querySelectorAll('tbody tr, tfoot tr').forEach(function (linha) {
                    const celula = linha.children[indice];
                    if (celula && celula.tagName.toLowerCase() === 'td') {
                        celula.classList.add('ets-print-action-column');
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>

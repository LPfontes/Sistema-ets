@extends('layouts.app')
@section('title', 'Dashboard - ETS/UFPB')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div><h2 class="fw-bold mb-1">Painel Administrativo</h2><p class="text-muted mb-0">Visão geral de cursos, ofertas e carga horária da ETS/UFPB.</p></div>
    <span class="badge text-bg-light border px-3 py-2">Dados atualizados do sistema</span>
</div>

{{-- Indicadores gerais: não dependem dos filtros do acompanhamento. --}}
<div class="row g-3 mb-4">
    @foreach ([['Cursos', $totalCursos, 'cursos.index'], ['Disciplinas', $totalDisciplinas, 'disciplinas.index'], ['Docentes', $totalDocentes, 'docentes.index'], ['Ofertas de Cursos', $totalOfertas, 'ofertas-cursos.index']] as $indicador)
    <div class="col-6 col-xl-3"><div class="card card-dashboard h-100"><div class="card-body">
        <div class="small text-muted mb-2">{{ $indicador[0] }}</div>
        <div class="fs-2 fw-bold">{{ $indicador[1] }}</div>
        <a class="small text-decoration-none" href="{{ route($indicador[2]) }}">Ver registros &rarr;</a>
    </div></div></div>
    @endforeach
</div>

<div class="card card-dashboard mb-4"><div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div><h4 class="fw-bold mb-1">Acompanhamento da Carga Horária</h4><p class="text-muted small mb-0">Consulte separadamente a carga horária oficial do curso e a execução das disciplinas ofertadas. Sem filtros, veja o total das ofertas vinculadas.</p></div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Limpar filtros</a>
    </div>
    {{-- GET permite compartilhar a URL dos filtros, sem alterar o banco. --}}
    <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-4"><label class="form-label fw-semibold" for="curso_id">Curso</label>
            <select class="form-select" name="curso_id" id="curso_id" onchange="this.form.submit()">
                <option value="">Todos os cursos</option>
                @foreach ($cursos as $curso)<option value="{{ $curso->id }}" @selected((string)$cursoId === (string)$curso->id)>{{ $curso->nome }}</option>@endforeach
            </select></div>
        <div class="col-md-4"><label class="form-label fw-semibold" for="oferta_id">Oferta do curso</label>
            <select class="form-select" name="oferta_id" id="oferta_id" onchange="this.form.submit()">
                <option value="">Todas as ofertas</option>
                @foreach ($ofertas as $oferta)
                    <option value="{{ $oferta->id }}" @selected((string)$ofertaId === (string)$oferta->id)>{{ $oferta->curso?->nome ?? 'Curso' }} — {{ $oferta->semestre?->identificador ?? 'Sem período' }} (Oferta)</option>
                @endforeach
            </select></div>
        <div class="col-md-3"><label class="form-label fw-semibold" for="semestre_id">Período letivo</label>
            <select class="form-select" name="semestre_id" id="semestre_id" onchange="this.form.submit()">
                <option value="">Todos os períodos</option>
                @foreach ($semestres as $semestre)<option value="{{ $semestre->id }}" @selected((string)$semestreId === (string)$semestre->id)>{{ $semestre->identificador }}</option>@endforeach
            </select></div>
        <div class="col-md-1"><button class="btn btn-primary w-100" type="submit" title="Aplicar filtros">Filtrar</button></div>
    </form>
    {{-- A CH oficial vem do cadastro do curso e não deve ser confundida com a CH das ofertas. --}}
    @if ($cursoSelecionado)
    <div class="rounded-3 border border-primary bg-primary bg-opacity-10 p-3 mb-3">
        <div class="text-primary fw-semibold mb-1">Carga horária oficial do curso</div>
        <div class="fs-3 fw-bold">{{ number_format((float) ($cursoSelecionado->carga_horaria_total ?? 0), 0, ',', '.') }}h</div>
        <div class="small text-muted">{{ $cursoSelecionado->nome }} — valor cadastrado para o curso inteiro, independente da oferta ou do período letivo.</div>
    </div>
    @else
    <div class="alert alert-info py-2 mb-3 small">Selecione um curso para consultar sua carga horária oficial. Os indicadores abaixo resumem as ofertas conforme os filtros.</div>
    @endif
    <h5 class="fw-semibold mb-2">Execução das disciplinas ofertadas</h5>
    <p class="text-muted small mb-3">As horas previstas e cumpridas abaixo consideram somente as disciplinas vinculadas às ofertas e ao período selecionados.</p>
    <div class="row g-3 mb-3">
        <div class="col-md-4"><div class="p-3 rounded-3 bg-light border h-100"><div class="text-muted small">Horas previstas nas ofertas</div><div class="fs-3 fw-bold">{{ number_format($previstas, 0, ',', '.') }}h</div></div></div>
        <div class="col-md-4"><div class="p-3 rounded-3 bg-light border h-100"><div class="text-muted small">Horas cumpridas nas ofertas</div><div class="fs-3 fw-bold text-success">{{ number_format($cumpridas, 0, ',', '.') }}h</div></div></div>
        <div class="col-md-4"><div class="p-3 rounded-3 bg-light border h-100"><div class="text-muted small">Horas restantes nas ofertas</div><div class="fs-3 fw-bold">{{ number_format($restantes, 0, ',', '.') }}h</div></div></div>
    </div>
    <div class="d-flex justify-content-between small mb-2"><span>Progresso da carga horária</span><strong>{{ number_format($percentual, 1, ',', '.') }}%</strong></div>
    <div class="progress" role="progressbar" aria-label="Carga horária cumprida" aria-valuenow="{{ round($percentual) }}" aria-valuemin="0" aria-valuemax="100" style="height:14px"><div class="progress-bar bg-success" style="width:{{ $percentual }}%"></div></div>
    <div class="text-muted small mt-2">{{ $disciplinasOfertadas }} oferta(s) de disciplina vinculada(s) no filtro selecionado. Disciplinas sem vínculo com uma oferta de curso não entram neste resumo.</div>
    @if ($disciplinasOfertadas === 0 && $cursoSelecionado)
        <div class="alert alert-warning mt-3 mb-0 small">Nenhuma disciplina ofertada foi encontrada para os filtros selecionados. Isso não significa que o curso não tenha carga horária oficial; confira o vínculo das disciplinas com a oferta e o período letivo.</div>
    @endif
    @if ($ofertaId)<a class="btn btn-outline-primary btn-sm mt-3" href="{{ route('ofertas-cursos.acompanhamento', $ofertaId) }}">Ver acompanhamento detalhado desta oferta</a>@endif
</div></div>

<div class="row g-4">
    <div class="col-xl-7"><div class="card card-dashboard h-100"><div class="card-body p-4">
        <h5 class="fw-bold mb-3">Ofertas de Cursos Recentes</h5>
        <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Curso</th><th>Início</th><th>Ação</th></tr></thead><tbody>
            @forelse ($ofertasRecentes as $oferta)
                <tr><td>{{ $oferta->curso?->nome ?? 'Curso não informado' }}</td><td>{{ $oferta->semestre?->identificador ?? '—' }}</td><td><a href="{{ route('ofertas-cursos.acompanhamento', $oferta->id) }}">Acompanhar</a></td></tr>
            @empty <tr><td colspan="3" class="text-muted">Nenhuma oferta cadastrada.</td></tr> @endforelse
        </tbody></table></div>
    </div></div></div>
    <div class="col-xl-5"><div class="card card-dashboard h-100"><div class="card-body p-4">
        <h5 class="fw-bold mb-3">Acesso rápido</h5>
        <div class="d-grid gap-2">
            <a class="btn btn-outline-primary text-start" href="{{ route('cursos.index') }}">Cursos</a>
            <a class="btn btn-outline-primary text-start" href="{{ route('ofertas-cursos.index') }}">Ofertas de Cursos</a>
            <a class="btn btn-outline-primary text-start" href="{{ route('registros-aulas.index') }}">Registro de Aulas</a>
            <a class="btn btn-outline-primary text-start" href="{{ route('extratos.semestre') }}">Extrato por Semestre</a>
        </div>
    </div></div></div>
</div>
@endsection

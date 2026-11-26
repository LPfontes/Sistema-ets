@extends('layouts.app')
@section('title', 'Detalhes do Docente')
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><h2 class="mb-1">{{ $docente->nome }}</h2>
            <p class="text-muted mb-0">Colegiado: {{ $docente->colegiado }} | SIAPE: {{ $docente->siape }}</p>
        </div>
        <a href="{{ route('docentes.index') }}" class="btn btn-outline-secondary">Voltar aos Docentes</a>
    </div>

    {{-- Resumo das cargas horárias das ofertas atribuídas ao docente. --}}
    <div class="row g-3 mb-4">
        @foreach([['Ofertas atribuídas', $ofertas->count(), ''], ['Horas previstas', $totalPrevisto, 'h'], ['Horas cumpridas', $totalCumprido, 'h'], ['Horas restantes', $totalRestante, 'h']] as $indicador)
            <div class="col-md-3 col-sm-6"><div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">{{ $indicador[0] }}</div>
                <h3 class="mb-0">{{ $indicador[1] }}{{ $indicador[2] }}</h3>
            </div></div></div>
        @endforeach
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Disciplinas ofertadas por este docente</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead><tr><th>Disciplina</th><th>Curso / Oferta</th><th>Semestre</th><th class="text-end">Previstas</th><th class="text-end">Cumpridas</th><th class="text-end">Restantes</th></tr></thead>
                    <tbody>
                        @forelse($ofertas as $oferta)
                            <tr>
                                <td>{{ $oferta->disciplina?->nome ?? 'Disciplina não encontrada' }}</td>
                                <td>{{ $oferta->ofertaCurso?->curso?->nome ?? 'Não vinculada' }}</td>
                                <td>{{ $oferta->semestre?->identificador ?? 'Não informado' }}</td>
                                <td class="text-end">{{ $oferta->carga_horaria_prevista ?? 0 }}h</td>
                                <td class="text-end">{{ $oferta->carga_horaria_cumprida ?? 0 }}h</td>
                                <td class="text-end">{{ max(0, ($oferta->carga_horaria_prevista ?? 0) - ($oferta->carga_horaria_cumprida ?? 0)) }}h</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Nenhuma oferta de disciplina atribuída a este docente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(auth()->user()?->perfil === 'admin')
            <div class="card-footer"><a href="{{ route('docentes.edit', $docente) }}" class="btn btn-warning">Editar Docente</a></div>
        @endif
    </div>
</div>
@endsection

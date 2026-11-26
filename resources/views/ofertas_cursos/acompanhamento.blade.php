{{-- Acompanhamento de carga horária da oferta de curso. --}}
@extends('layouts.app')
@section('title', 'Acompanhamento da Oferta')
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2>Acompanhamento da Oferta de Curso</h2>
            <p class="text-muted mb-0">{{ $ofertaCurso->curso?->nome ?? 'Curso não informado' }}</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('ofertas-cursos.index') }}">Voltar às ofertas</a>
    </div>

    {{-- Dados da oferta, separados da situação de cumprimento das horas. --}}
    <div class="card shadow-sm mb-4"><div class="card-body">
        <div class="row g-3">
            <div class="col-md-3"><strong>Início</strong><div>{{ $ofertaCurso->semestre?->identificador ?? 'Não informado' }}</div></div>
            <div class="col-md-3"><strong>Fim previsto</strong><div>{{ $ofertaCurso->semestreFinal?->identificador ?? 'Não informado' }}</div></div>
            <div class="col-md-3"><strong>Turmas</strong><div>{{ $ofertaCurso->quantidade_turmas }}</div></div>
            <div class="col-md-3"><strong>Situação cadastrada</strong><div>{{ $ofertaCurso->situacao }}</div></div>
        </div>
    </div></div>

    {{-- Indicadores calculados somente a partir das ofertas de disciplinas vinculadas. --}}
    <div class="row g-3 mb-4">
        @foreach ([['Previstas', $previstas], ['Cumpridas', $cumpridas], ['Restantes', $restantes]] as [$titulo, $horas])
            <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted">{{ $titulo }}</div>
                <h3 class="mb-0">{{ number_format($horas, 2, ',', '.') }}h</h3>
            </div></div></div>
        @endforeach
    </div>

    <div class="card shadow-sm mb-4"><div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>Percentual de carga horária cumprida</strong>
            <strong>{{ number_format($percentual, 2, ',', '.') }}%</strong>
        </div>
        <div class="progress" role="progressbar" aria-label="Percentual cumprido" aria-valuenow="{{ round($percentual) }}" aria-valuemin="0" aria-valuemax="100" style="height: 18px;">
            <div class="progress-bar {{ $integralizada ? 'bg-success' : 'bg-primary' }}" style="width: {{ $percentual }}%"></div>
        </div>
        <div class="mt-3">
            @if ($disciplinas->isEmpty())
                <span class="badge bg-warning text-dark">Sem disciplinas vinculadas</span>
            @elseif ($integralizada)
                <span class="badge bg-success">Carga horária vinculada integralizada</span>
            @else
                <span class="badge bg-primary">Carga horária em acompanhamento</span>
            @endif
        </div>
        <small class="text-muted d-block mt-2">O percentual considera apenas disciplinas vinculadas a esta oferta. A integralização das horas não comprova, por si só, a conclusão acadêmica do curso.</small>
    </div></div>

    <div class="card shadow-sm"><div class="card-header"><h5 class="mb-0">Disciplinas da oferta</h5></div>
        <div class="card-body">
            @if ($disciplinas->isEmpty())
                <div class="alert alert-info mb-0">Nenhuma disciplina está vinculada a esta oferta de curso. Vincule as disciplinas no cadastro de Ofertas de Disciplinas para calcular o acompanhamento. Ofertas avulsas não são incluídas automaticamente.</div>
            @else
                <div class="table-responsive"><table class="table table-striped table-hover align-middle">
                    <thead><tr><th>Período</th><th>Disciplina</th><th>Docente</th><th>Previstas</th><th>Cumpridas</th><th>Restantes</th><th>Percentual</th></tr></thead>
                    <tbody>
                    @foreach ($disciplinas as $item)
                        @php
                            // Calcula o progresso da disciplina, evitando divisão por zero.
                            $p = (float) $item->carga_horaria_prevista;
                            $c = (float) $item->carga_horaria_cumprida;
                            $r = max(0, $p - $c);
                            $pct = $p > 0 ? min(100, $c / $p * 100) : 0;
                        @endphp
                        <tr>
                            <td>{{ $item->semestre?->identificador ?? '-' }}</td>
                            <td>{{ $item->disciplina?->nome ?? 'Não informada' }}</td>
                            <td>{{ $item->docente?->nome ?? 'Não atribuído' }}</td>
                            <td>{{ number_format($p, 2, ',', '.') }}h</td>
                            <td>{{ number_format($c, 2, ',', '.') }}h</td>
                            <td>{{ number_format($r, 2, ',', '.') }}h</td>
                            <td style="min-width: 135px;">
                                <strong>{{ number_format($pct, 2, ',', '.') }}%</strong>
                                <div class="progress mt-1" style="height: 7px;"><div class="progress-bar {{ $p > 0 && $r <= 0 ? 'bg-success' : '' }}" style="width: {{ $pct }}%"></div></div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            @endif
        </div>
    </div>
</div>
@endsection

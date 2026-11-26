@extends('layouts.app')
@section('title', 'Extrato de Carga Horária')
@section('content')
<div class="container-fluid">
<h2>Extrato por Docente</h2>
<p class="text-muted">Horas previstas, cumpridas (registradas em aulas ou como horas anteriores) e restantes. Ofertas sem associação a curso/turma são identificadas como avulsas.</p>
@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="GET" action="{{ route('extratos.docente') }}" class="card card-body mb-4"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Docente</label><select name="docente_id" class="form-select"><option value="">Todos os docentes</option>@foreach($docentes as $professor)<option value="{{ $professor->id }}" @selected(request('docente_id') == $professor->id)>{{ $professor->nome }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">De</label><input name="inicio" class="form-control" placeholder="2026.2" value="{{ request('inicio') }}"></div>
<div class="col-md-3"><label class="form-label">Até</label><input name="fim" class="form-control" placeholder="2028.1" value="{{ request('fim') }}"></div></div>
<div class="mt-3"><button class="btn btn-primary">Filtrar</button> <a class="btn btn-outline-secondary" href="{{ route('extratos.docente') }}">Limpar</a></div></form>
{{-- Progresso geral: evita divisão por zero quando não existem horas previstas. --}}
@php($percentualGeral = $totais['previstas'] > 0 ? min(100, max(0, ($totais['cumpridas'] / $totais['previstas']) * 100)) : 0)
<div class="card card-body mb-3">
    <strong>Total no filtro: {{ $totais['previstas'] }}h previstas · {{ $totais['cumpridas'] }}h cumpridas · {{ $totais['restantes'] }}h restantes</strong>
    <div class="d-flex justify-content-between mt-2"><span>Percentual cumprido</span><strong>{{ number_format($percentualGeral, 2, ',', '.') }}%</strong></div>
    <div class="progress mt-1" role="progressbar" aria-label="Progresso geral da carga horária" aria-valuenow="{{ round($percentualGeral) }}" aria-valuemin="0" aria-valuemax="100" style="height: 12px;">
        <div class="progress-bar {{ $percentualGeral >= 100 ? 'bg-success' : '' }}" style="width: {{ $percentualGeral }}%"></div>
    </div>
</div>
@forelse($linhas as $linha)<div class="card mb-3"><div class="card-header"><strong>{{ $linha['docente']?->nome }}</strong> — {{ $linha['totais']['previstas'] }}h previstas / {{ $linha['totais']['cumpridas'] }}h cumpridas / {{ $linha['totais']['restantes'] }}h restantes</div>
{{-- Progresso da carga horária deste grupo (semestre ou docente). --}}
@php($percentualGrupo = $linha['totais']['previstas'] > 0 ? min(100, max(0, ($linha['totais']['cumpridas'] / $linha['totais']['previstas']) * 100)) : 0)
<div class="px-3 pt-2 pb-2">
    <div class="d-flex justify-content-between"><small>Percentual cumprido</small><strong>{{ number_format($percentualGrupo, 2, ',', '.') }}%</strong></div>
    <div class="progress mt-1" role="progressbar" aria-label="Progresso do grupo" aria-valuenow="{{ round($percentualGrupo) }}" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
        <div class="progress-bar {{ $percentualGrupo >= 100 ? 'bg-success' : '' }}" style="width: {{ $percentualGrupo }}%"></div>
    </div>
</div>
<div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>Período</th><th>Disciplina</th><th>Curso</th><th>Oferta</th><th>Previstas</th><th>Cumpridas</th><th>Restantes</th><th>Progresso</th></tr></thead><tbody>
@foreach($linha['ofertas'] as $item)<tr><td>{{ $item->semestre?->identificador }}</td><td>{{ $item->disciplina?->nome }}</td><td>{{ $item->disciplina?->curso?->nome }}</td><td>{{ $item->oferta_curso_id ? '#'.$item->oferta_curso_id : 'Avulsa' }}</td><td>{{ $item->carga_horaria_prevista }}h</td><td>{{ $item->carga_horaria_cumprida }}h</td><td>{{ max(0, $item->carga_horaria_prevista - $item->carga_horaria_cumprida) }}h</td>
{{-- Calcula o progresso individual da oferta de disciplina. --}}
@php($percentualItem = $item->carga_horaria_prevista > 0 ? min(100, max(0, ($item->carga_horaria_cumprida / $item->carga_horaria_prevista) * 100)) : 0)
<td style="min-width: 130px;">
    <strong>{{ number_format($percentualItem, 2, ',', '.') }}%</strong>
    <div class="progress mt-1" role="progressbar" aria-label="Progresso da disciplina" aria-valuenow="{{ round($percentualItem) }}" aria-valuemin="0" aria-valuemax="100" style="height: 8px;">
        <div class="progress-bar {{ $percentualItem >= 100 ? 'bg-success' : '' }}" style="width: {{ $percentualItem }}%"></div>
    </div>
</td></tr>@endforeach
</tbody></table></div></div>
@empty<div class="alert alert-info">Nenhuma oferta atribuída a docente encontrada.</div>@endforelse
</div>
@endsection

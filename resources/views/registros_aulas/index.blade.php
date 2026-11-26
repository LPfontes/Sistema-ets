@extends('layouts.app')
@section('title', 'Registro de Aulas e Carga Horária')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Registro de Aulas e Carga Horária</h2>
        <a class="btn btn-primary" href="{{ route('registros-aulas.create') }}">+ Registrar Aula</a>
    </div>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <form method="GET" class="card card-body mb-3">
        <label class="form-label" for="oferta_disciplina_id">Filtrar por oferta de disciplina</label>
        <div class="d-flex gap-2">
            <select class="form-select" name="oferta_disciplina_id" id="oferta_disciplina_id">
                <option value="">Todas as ofertas</option>
                @foreach($ofertas as $oferta)
                    <option value="{{ $oferta->id }}" @selected(($filtros['oferta_disciplina_id'] ?? '') == $oferta->id)>
                        #{{ $oferta->id }} — {{ $oferta->disciplina?->nome }} — {{ $oferta->semestre?->identificador }} — {{ $oferta->docente?->nome ?? 'Sem docente' }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-outline-primary">Filtrar</button>
        </div>
    </form>
    <div class="card shadow-sm"><div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead><tr><th>Data</th><th>Disciplina / Período</th><th>Docente</th><th>Tipo</th><th>Conteúdo</th><th>Horas</th><th>Ações</th></tr></thead>
            <tbody>
            @forelse($registros as $registro)
                <tr>
                    <td>{{ $registro->data_aula->format('d/m/Y') }}</td>
                    <td>{{ $registro->ofertaDisciplina?->disciplina?->nome }}<br><small>{{ $registro->ofertaDisciplina?->semestre?->identificador }}</small></td>
                    <td>{{ $registro->ofertaDisciplina?->docente?->nome ?? 'Não informado' }}</td>
                    <td>{{ $registro->tipo_atividade }}</td>
                    <td>{{ $registro->conteudo_ministrado }}</td>
                    <td>{{ $registro->horas_realizadas }}h</td>
                    <td class="text-nowrap">
                        <a class="btn btn-sm btn-warning" href="{{ route('registros-aulas.edit', $registro) }}">Editar</a>
                        <form method="POST" action="{{ route('registros-aulas.destroy', $registro) }}" class="d-inline" onsubmit="return confirm('Excluir este registro de aula?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Nenhuma aula registrada.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
    <div class="mt-3">{{ $registros->links() }}</div>
    <p class="text-muted mt-3">As horas cumpridas são calculadas a partir dos registros de aulas, somadas às horas anteriores já informadas.</p>
</div>
@endsection

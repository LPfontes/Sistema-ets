{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título exibido na aba do navegador --}}
@section('title', 'Extrato de Carga Horária por Curso')

{{-- Início do conteúdo principal --}}
@section('content')

<div class="container-fluid">

    {{-- Título da página --}}
    <h2 class="mb-3">
        Extrato de Carga Horária por Curso
    </h2>

    {{-- Explicação da funcionalidade --}}
    <p class="text-muted">
        Selecione um curso para visualizar a carga horária
        dos módulos e disciplinas por semestre curricular.
    </p>

    {{-- Card que contém o formulário --}}
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            Selecionar Curso
        </div>

        <div class="card-body">

            {{--
                FORMULÁRIO DE SELEÇÃO

                O formulário utiliza GET porque apenas
                consulta informações do sistema.

                O JavaScript direciona o usuário para
                a rota de análise do curso selecionado.
            --}}

            <form id="formExtratoCurso">

                {{-- Campo de seleção do curso --}}
                <div class="mb-3">

                    <label for="curso_id" class="form-label">
                        Curso
                    </label>

                    <select id="curso_id"
                            class="form-select"
                            required>

                        <option value="">
                            Selecione um curso
                        </option>

                        {{-- Lista os cursos cadastrados --}}
                        @foreach ($cursos as $curso)

                            <option value="{{ $curso->id }}">
                                {{ $curso->nome }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Botão para gerar o relatório --}}
                <button type="submit"
                        class="btn btn-primary">
                    Gerar Extrato
                </button>

            </form>

        </div>
    </div>

</div>

{{-- Script responsável pelo redirecionamento --}}
<script>
    // Localiza o formulário na página.
    const formulario = document.getElementById('formExtratoCurso');

    // Executa quando o usuário clica em Gerar Extrato.
    formulario.addEventListener('submit', function (evento) {

        // Impede o envio padrão do formulário.
        evento.preventDefault();

        // Obtém o ID do curso selecionado.
        const cursoId = document.getElementById('curso_id').value;

        // Verifica se um curso foi selecionado.
        if (!cursoId) {
            alert('Selecione um curso.');
            return;
        }

        // URL gerada pelo Laravel usando a rota existente.
        // O valor temporário será substituído pelo ID selecionado.
        const urlModelo = @json(
            route('cursos.analise-carga-horaria', ['curso' => '__CURSO__'])
        );

        // Substitui o identificador temporário pelo ID do curso.
        const url = urlModelo.replace('__CURSO__', encodeURIComponent(cursoId));

        // Abre a página de análise de carga horária.
        window.location.href = url;
    });
</script>

@endsection

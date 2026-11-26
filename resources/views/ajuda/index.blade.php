@extends('layouts.app')
@section('title', 'Manual do Usuário - ETS/UFPB')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Manual do Usuário</h2>
        <p class="text-muted mb-0">Sistema de Análise de Cargas Horárias de Cursos — ETS/UFPB</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">Voltar ao Dashboard</a>
</div>

<div class="alert alert-info" role="note">
    <strong>Como usar este manual:</strong> abra os grupos do menu lateral para acessar as funcionalidades.
    O administrador pode gerenciar os cadastros; o visitante tem acesso de consulta.
</div>

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">1. Acesso e perfis</h5>
    <p>Entre com seu e-mail e senha na página de login. Para encerrar a sessão, clique em <strong>Sair</strong>, no canto superior direito.</p>
    <p class="mb-0"><strong>Administrador:</strong> gerencia os registros e os visitantes. <strong>Visitante:</strong> consulta as informações, sem permissão para cadastrar, editar ou excluir.</p>
</div></div>

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">2. Menu lateral recolhível</h5>
    <p>Clique no nome de um grupo para expandir ou recolher suas opções:</p>
    <ul class="mb-0">
        <li><strong>Gestão Acadêmica:</strong> Cursos, Módulos, Semestres, Disciplinas e Docentes.</li>
        <li><strong>Ofertas e Aulas:</strong> Ofertas de Cursos, Ofertas de Disciplinas e Registro de Aulas e Carga Horária.</li>
        <li><strong>Extratos de Carga Horária:</strong> Por Curso, Por Semestre e Por Docente.</li>
        @if(auth()->user()->perfil === 'admin')
        <li><strong>Administração:</strong> Visitantes e Relatório de Acesso dos Visitantes.</li>
        @endif
        <li><strong>Conta e Ajuda:</strong> Meu Perfil (administrador) e Manual do Usuário.</li>
    </ul>
</div></div>

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">3. Novo Dashboard</h5>
    <p>O Dashboard apresenta quatro indicadores gerais: <strong>Cursos</strong>, <strong>Disciplinas</strong>, <strong>Docentes</strong> e <strong>Ofertas de Cursos</strong>. Os números são obtidos dos registros cadastrados no sistema e não mudam com os filtros de carga horária.</p>
    <p>Na parte inferior, consulte as <strong>Ofertas de Cursos Recentes</strong> e use os links <strong>Acompanhar</strong> para abrir o acompanhamento detalhado. A seção <strong>Acesso rápido</strong> contém atalhos para Cursos, Ofertas de Cursos, Registro de Aulas e Extrato por Semestre.</p>
    <p class="mb-0">Os avisos antigos de funcionalidades em desenvolvimento foram retirados do Dashboard.</p>
</div></div>

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">4. Acompanhamento da Carga Horária no Dashboard</h5>
    <p>O painel <strong>Acompanhamento da Carga Horária</strong> permite selecionar <strong>Curso</strong>, <strong>Oferta do curso</strong> e <strong>Período letivo</strong>.</p>
    <ol>
        <li>Selecione um curso ou deixe <strong>Todos os cursos</strong> para uma visão geral.</li>
        <li>Selecione a oferta específica, quando quiser acompanhar uma turma/oferta determinada. O mesmo curso pode possuir mais de uma oferta.</li>
        <li>Selecione o período letivo, por exemplo <strong>2026.2</strong>, se desejar restringir o acompanhamento.</li>
        <li>Clique em <strong>Filtrar</strong> quando necessário. Os campos também aplicam os filtros ao serem alterados.</li>
        <li>Para voltar ao resumo geral, clique em <strong>Limpar filtros</strong>.</li>
    </ol>
    <p>O resultado mostra <strong>Horas previstas</strong>, <strong>Horas cumpridas</strong>, <strong>Horas restantes</strong> e o <strong>percentual de progresso</strong>.</p>
    <p>Quando uma oferta específica está selecionada, aparece o botão <strong>Ver acompanhamento detalhado desta oferta</strong>.</p>
    <div class="alert alert-warning mb-0"><strong>Atenção:</strong> o resumo considera as ofertas de disciplinas vinculadas às ofertas de cursos no filtro selecionado. Disciplinas sem esse vínculo não entram no cálculo. As horas cumpridas refletem os registros de execução consolidados no sistema.</div>
</div></div>

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">5. Gestão Acadêmica: cursos e módulos</h5>
    <p>Em <strong>Cursos</strong>, o administrador cadastra e edita cursos. É possível informar a modalidade, incluindo <strong>EAD</strong>, e anexar opcionalmente o <strong>Projeto Pedagógico do Curso (PPC)</strong> em PDF, DOC ou DOCX.</p>
    <p>Para cadastrar módulos sem sobrecarregar o formulário do curso, utilize a opção <strong>Salvar e Adicionar Módulo</strong>. O curso é salvo primeiro e o sistema abre a página separada de cadastro do módulo, já associado ao curso.</p>
    <p class="mb-0">Os demais cadastros estão em <strong>Módulos</strong>, <strong>Semestres</strong>, <strong>Disciplinas</strong> e <strong>Docentes</strong>.</p>
</div></div>

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">6. Ofertas, aulas e carga horária</h5>
    <p>Em <strong>Ofertas de Cursos</strong>, consulte as ofertas por período e acesse seu acompanhamento. Em <strong>Ofertas de Disciplinas</strong>, consulte as disciplinas ofertadas e seus vínculos.</p>
    <p class="mb-0">Em <strong>Registro de Aulas e Carga Horária</strong>, o administrador informa a aula realizada, sua data, tipo, conteúdo e quantidade de horas. Esses registros alimentam o acompanhamento e os extratos.</p>
</div></div>

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">7. Extratos de Carga Horária</h5>
    <p class="mb-0">Expanda <strong>Extratos de Carga Horária</strong> e escolha <strong>Por Curso</strong>, <strong>Por Semestre</strong> ou <strong>Por Docente</strong>. Consulte as horas previstas, cumpridas e restantes de acordo com os registros e filtros disponíveis em cada página.</p>
</div></div>

@if(auth()->user()->perfil === 'admin')
<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">8. Administração de visitantes</h5>
    <p>Em <strong>Administração → Visitantes</strong>, cadastre visitantes. O sistema gera uma senha inicial automaticamente; copie-a quando for apresentada e compartilhe-a de forma segura com a pessoa autorizada.</p>
    <p>Em <strong>Relatório de Acesso dos Visitantes</strong>, consulte os registros de entrada e saída dos visitantes.</p>
    <p class="mb-0">Em <strong>Conta e Ajuda → Meu Perfil</strong>, o administrador pode atualizar sua senha e sua foto de perfil.</p>
</div></div>
@endif

<div class="card card-dashboard mb-3"><div class="card-body">
    <h5 class="fw-bold">9. Boas práticas</h5>
    <ul class="mb-0">
        <li>Confira curso, oferta e período antes de interpretar os totais de carga horária.</li>
        <li>Registre as aulas realizadas para manter os extratos atualizados.</li>
        <li>Não compartilhe senhas e sempre utilize <strong>Sair</strong> ao terminar.</li>
        <li>Antes de alterações importantes nos cadastros, confira se os dados estão corretos.</li>
    </ul>
</div></div>
@endsection

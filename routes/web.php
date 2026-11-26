<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtratoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\CursoController;

// Importa o Controller responsável pelos módulos.
use App\Http\Controllers\ModuloController;

// Importa o Controller de Semestres.
use App\Http\Controllers\SemestreController;

// Importa o Controller de Ofertas de Disciplinas.
use App\Http\Controllers\OfertaDisciplinaController;

// Importa o Controller responsável pelo CRUD de Disciplinas.
// Adicione esta linha junto às outras importações.
// Não repita a abertura <?php se ela já existir no arquivo.
use App\Http\Controllers\DisciplinaController;


/*
 * CONTROLLER DE OFERTAS DE CURSOS
 *
 * Responsável pelas operações de:
 * - Listagem;
 * - Cadastro;
 * - Visualização;
 * - Edição;
 * - Exclusão de ofertas de cursos.
 */
use App\Http\Controllers\OfertaCursoController;





Route::middleware('auth')->group(function () {

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');


// CRUD de Docentes
Route::resource('docentes', DocenteController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('docentes', DocenteController::class)->only(['index', 'show']);


/*
|--------------------------------------------------------------------------
| ANÁLISE DE CARGA HORÁRIA POR CURSO
|--------------------------------------------------------------------------
|
| Esta rota permite consultar a carga horária de um curso.
|
| O parâmetro {curso} identifica o curso selecionado.
|
| O método analisarCargaHoraria() calcula:
| - Carga horária oficial dos módulos;
| - Carga horária das disciplinas cadastradas;
| - Diferença entre as cargas horárias;
| - Totais por semestre curricular.
|
| A rota utiliza GET porque apenas consulta informações.
|
*/
Route::get(
    '/cursos/{curso}/analise-carga-horaria',
    [CursoController::class, 'analisarCargaHoraria']
)->name('cursos.analise-carga-horaria');


/*
 * EXTRATO DE CARGA HORÁRIA POR CURSO
 *
 * Exibe a página para selecionar o curso
 * que será utilizado na análise curricular.
 */
Route::get(
    '/extratos/curso',
    [CursoController::class, 'selecionarCursoExtrato']
)->name('extratos.curso');


Route::resource('cursos', CursoController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('cursos', CursoController::class)->only(['index', 'show']);



/*
 * ROTAS DO CRUD DE MÓDULOS
 *
 * Route::resource():
 * Cria automaticamente as sete rotas
 * necessárias para as operações do CRUD.
 *
 * Exemplos:
 * GET    /modulos
 * GET    /modulos/create
 * POST   /modulos
 * GET    /modulos/{modulo}
 * GET    /modulos/{modulo}/edit
 * PUT    /modulos/{modulo}
 * DELETE /modulos/{modulo}
 */
Route::resource('modulos', ModuloController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('modulos', ModuloController::class)->only(['index', 'show']);


/*
 * ROTAS DO CRUD DE SEMESTRES
 *
 * Cria automaticamente as sete rotas
 * necessárias para cadastrar, listar,
 * visualizar, editar e excluir semestres.
 */
Route::resource('semestres', SemestreController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('semestres', SemestreController::class)->only(['index', 'show']);


// =====================================================
// US2 - ROTAS DO CRUD DE DISCIPLINAS
// =====================================================

// Cria automaticamente as sete rotas necessárias
// para cadastrar, listar, visualizar, editar,
// atualizar e excluir disciplinas.
Route::resource('disciplinas', DisciplinaController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('disciplinas', DisciplinaController::class)->only(['index', 'show']);


// Cria automaticamente as 7 rotas do CRUD.
// Exemplo: /ofertas-disciplinas
Route::resource('ofertas-disciplinas', OfertaDisciplinaController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('ofertas-disciplinas', OfertaDisciplinaController::class)->only(['index', 'show']);


/*
 * ROTAS DE OFERTAS DE CURSOS — US5
 *
 * Route::resource cria automaticamente
 * as sete rotas necessárias para o CRUD.
 *
 * Exemplos:
 * GET    /ofertas-cursos          -> index()
 * GET    /ofertas-cursos/create   -> create()
 * POST   /ofertas-cursos          -> store()
 * GET    /ofertas-cursos/{id}     -> show()
 * GET    /ofertas-cursos/{id}/edit -> edit()
 * PUT    /ofertas-cursos/{id}     -> update()
 * DELETE /ofertas-cursos/{id}     -> destroy()
 *
 * names() define o prefixo dos nomes das rotas.
 */
Route::resource('ofertas-cursos', OfertaCursoController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('ofertas-cursos', OfertaCursoController::class)->only(['index', 'show']);




// Extratos baseados em horas previstas e horas efetivamente registradas.
Route::get('/extratos/semestre', [ExtratoController::class, 'semestre'])->name('extratos.semestre');
Route::get('/extratos/docente', [ExtratoController::class, 'docente'])->name('extratos.docente');

// Registro de aulas: CRUD de horas efetivamente ministradas.
Route::resource('registros-aulas', \App\Http\Controllers\RegistroAulaController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('admin');
Route::resource('registros-aulas', \App\Http\Controllers\RegistroAulaController::class)->only(['index']);

// Acompanhamento da carga horária de uma oferta específica de curso.
Route::get('/ofertas-cursos/{ofertaCurso}/acompanhamento',
    [\App\Http\Controllers\AcompanhamentoOfertaController::class, 'show'])
    ->name('ofertas-cursos.acompanhamento');

}); // Fim das rotas protegidas por login.

// Rota de Single Sign-On (SSO) a partir do Portal Gateway Unificado
Route::get('/sso/login', [\App\Http\Controllers\Auth\LoginController::class, 'sso'])->name('sso.login');

// US6: páginas de login acessíveis somente a visitantes não autenticados.
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store'])->middleware('throttle:6,1');
});
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// US6/US7: gestão de visitantes e histórico de acessos (somente ADM).
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('visitantes', \App\Http\Controllers\VisitanteController::class)->except(['show']);
    Route::get('/relatorios/acessos', [\App\Http\Controllers\RelatorioAcessoController::class, 'index'])->name('relatorios.acessos');
});

// US8: ajuda disponível a todos os usuários autenticados.
Route::get('/ajuda', fn () => view('ajuda.index'))->middleware('auth')->name('ajuda');

// Perfil do ADM: troca de senha e foto.
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/meu-perfil', [\App\Http\Controllers\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/meu-perfil/senha', [\App\Http\Controllers\PerfilController::class, 'senha'])->name('perfil.senha')->middleware('throttle:6,1');
    Route::post('/meu-perfil/foto', [\App\Http\Controllers\PerfilController::class, 'foto'])->name('perfil.foto');
    Route::post('/visitantes/{visitante}/regenerar-senha', [\App\Http\Controllers\VisitanteController::class, 'regenerarSenha'])->name('visitantes.regenerar-senha')->middleware('throttle:10,1');
});

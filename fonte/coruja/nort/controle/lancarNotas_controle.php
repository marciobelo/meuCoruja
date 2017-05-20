<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";

require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/Turma.php";

// Verifica Permissão
if(!$usuario->temPermissao($LANCAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

$acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}
if( $acao == NULL)
{
    $acao = "buscarTurmasFiltro";
}


switch ($acao) {
    case 'buscarTurmasFiltro':
    case 'buscarTurmasResultado':
        /*
         *  O FILTRO É EXIBIDO TANTO AO REALIZAR A BUSCA QUANTO AO EXIBIR O RESULTADO
         */

        $arrayPeriodoLetivo = NULL;
        $arrayTurno = array('MANHÃ','TARDE','NOITE');
        $estaDesabilitadoPeriodo = 'DISABLED';
        $estaDesabilitado = 'DISABLED';

        $arrayCursos = Curso::obterCursosOrdemPorSigla();

        if( $siglaCursoFiltro !== "")
        {
            $siglaCurso = $siglaCursoFiltro;
        }
        else
        {
            $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
        }
        if( $siglaCurso)
        {
            $estaDesabilitadoPeriodo = '';
            $arrayPeriodoLetivo = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso( $siglaCurso);

            if ($_POST['idPeriodoLetivo']) {
                $estaDesabilitado = '';
            }
        }
        require "$BASE_DIR/nort/formularios/lancarNotas/lancarNotas_turmasFiltro.php";

        if( $acao !== 'buscarTurmasResultado') 
        {
            //CASO A AÇÃO SEJA APENAS DE EXIBIR O FILTRO, TERMINA O PROCESSAMENTO, CASO CONTRATIO, CONTINUA O
            //PROCESSAMENTO PARA QUE SEJAM EXIBIDAS AS TURMAS
            require_once "$BASE_DIR/includes/rodape.php";
            break;
        }
    case 'buscarTurmasResultado':
        //MOSTRA OS RESULTAODOS PARA QUE O USUÁRIO SELECIONE AS TURMAS


        $con = BD::conectar();


        //FILTROS ESPECIFICOS
        // PERIODO LETIVO
        if ($_POST['idPeriodoLetivo'])
            $filtroPeriodoLetivo = ' AND PL.`idPeriodoLetivo` = ' . mysql_real_escape_string ($_POST['idPeriodoLetivo']) . ' ';
        else
            $filtroPeriodoLetivo = '';

        // CURSO
        if ($_POST['siglaCurso'])
            $filtroCurso = ' AND T.`siglaCurso` = "' . mysql_real_escape_string ($_POST['siglaCurso']) . '" ';
        else
            $filtroCurso = '';

        // DISCIPLINA
        if ($_POST['turno'])
            $filtroTurno = ' AND T.`turno` = "' . mysql_real_escape_string ($_POST['turno']) . '" ';
        else
            $filtroTurno = '';

        // PROFESSOR
        if ($_POST['idPessoaProfessor'])
            $filtroProfessor = 'AND MP.`idPessoa` = ' . mysql_real_escape_string ($_POST['idPessoaProfessor']) . ' ';
        else
            $filtroProfessor = '';

        //COMANDO SQL FORMADO COM OS FILTROS
        $SQL_TURMAS =
                'SELECT DISTINCT T.`idTurma` , T.`siglaDisciplina` , CC.`nomeDisciplina`, P.`nome` ,  '
                . 'T.`turno`, T.`gradeHorario`, T.`tipoSituacaoTurma` , PL.`siglaPeriodoLetivo` '
                . 'FROM `ComponenteCurricular` CC, `PeriodoLetivo` PL, `Turma` T '
                . 'left join `MatriculaProfessor` MP '
                . 'on MP.`matriculaProfessor` = T.`matriculaProfessor` '
                . 'left join `Pessoa` P '
                . 'on MP.`idPessoa` = P.`idPessoa` '
                . 'WHERE PL.`idPeriodoLetivo` = T.`idPeriodoLetivo` '
                . 'AND CC.`siglaCurso` = T.`siglaCurso` '
                . 'AND CC.`idMatriz` = T.`idMatriz` '
                . 'AND CC.`siglaDisciplina` = T.`siglaDisciplina` '
                . "AND T.`tipoSituacaoTurma` in ('CONFIRMADA','FINALIZADA') "
                . $filtroPeriodoLetivo . chr(10)
                . $filtroCurso . chr(10)
                . $filtroTurno . chr(10)
                . 'ORDER BY T.`turno`, T.`gradeHorario`, T.`siglaDisciplina`  ASC';

        $result = mysql_query($SQL_TURMAS);

        if (mysql_num_rows($result) == 0) {
            ?><h4>Nenhuma turma encontrada</h4><?php
        } else {
            $arrayTurmas = array();
            while ($row = mysql_fetch_array($result)) {
                $arrayTurmas[] = $row;
            }
            require "$BASE_DIR/nort/formularios/lancarNotas/lancarNotas_turmasResultado.php";
            require_once "$BASE_DIR/includes/rodape.php";
        }

        break;

    case 'consultarNotas':
        //Esta ação espera receber $_POST['idTurma'] e $_POST['voltar_turno']
        $turma = Turma::getTurmaById($_POST['idTurma']);
        $listaDeIncricoes = getAlunosNaTurma($turma->getIdTurma());

        require "$BASE_DIR/nort/formularios/lancarNotas/lancarNotas_consultarNotas.php";
        require_once "$BASE_DIR/includes/rodape.php";
        registrarLog($turma);
        break;
    default :
        trigger_error( "Erro ao identificar acao do controlador", E_USER_ERROR);
        break;
}


function getAlunosNaTurma($idTurma) {

    /*
     * Atenção !
     * Esta função não carrega todos os atributos (idTurma, parecerIncricao e data da incricao)
     * Cuidado ao utilizar o objeto retornado por ela
     */

    $listaDeAlunos = array();
    $conn = BD::conectar();
    // lista os alunos da turma
    $queryListaAlunos = sprintf("SELECT

                                i.`matriculaAluno`, p.`nome`,
                                i.`mediaFinal`, i.`totalFaltas`, i.`situacaoInscricao`
                                
                                FROM Inscricao i
                                INNER JOIN MatriculaAluno m
                                ON i.matriculaAluno = m.matriculaAluno
                                INNER JOIN Pessoa p
                                ON m.idPessoa = p.idPessoa
                                INNER JOIN Turma t
                                ON t.idTurma = i.idTurma
                                INNER JOIN ComponenteCurricular c
                                ON t.siglaDisciplina = c.siglaDisciplina
                                AND c.idMatriz = t.idMatriz
                                WHERE i.idTurma = %d
                                AND situacaoInscricao IN ('CUR','AP','RM','RF','ID')
                                order by p.nome",
                    mysql_real_escape_string($idTurma)
    );
    $result=mysql_query($queryListaAlunos,$conn);

        while( $aluno = mysql_fetch_array($result) ) {
                $umaInscricao = new Inscricao();

                $umaInscricao->setMatriculaAluno($aluno['matriculaAluno']);
                $umaInscricao->setNomeAluno($aluno['nome']);
                $umaInscricao->setMediaFinal($aluno['mediaFinal']);
                $umaInscricao->setTotalFaltas($aluno['totalFaltas']);
                $umaInscricao->setSituacaoInscricao($aluno['situacaoInscricao']);
                $listaDeAlunos[] = $umaInscricao;
        }

        return $listaDeAlunos;
}

function registrarLog($turma) {
    $siglaCurso = $turma->getSiglaCurso();
    $nomeCurso = $turma->getCurso()->getNomeCurso();
    $siglaPeriodoLetivo = $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo();
    $turno = $turma->getTurno();
    $gradeHorario = $turma->getGradeHorario();
    $siglaDisciplina = $turma->getSiglaDisciplina();
    $nomeDisciplina = $turma->getComponenteCurricular()->getNomeDisciplina();

    if($turma->getProfessor()){
        $nomeProfessor = $turma->getProfessor()->getNome();
    } else {
        $nomeProfessor = "Sem Professor";
    }

    $traco = utf8_decode("-");

    $mensagem = "Consultada as notas e situações dos alunos de uma turma, do curso ";
    $mensagem .= "$siglaCurso $traco $nomeCurso, Período Letivo $siglaPeriodoLetivo, ";
    $mensagem .= "Turno $turno, Grade $gradeHorario, da disciplina $siglaDisciplina $traco $nomeDisciplina, ";
    $mensagem .= "Professor $nomeProfessor";

    global $LANCAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA;
    $_SESSION["usuario"]->incluirLog($LANCAR_NOTAS_E_SITUACAO_DO_ALUNO_EM_TURMA, $mensagem);
}
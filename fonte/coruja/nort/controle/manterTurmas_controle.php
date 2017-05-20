<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Mensagem.php";
require_once "$BASE_DIR/nort/includes/manterTurmas_obterMatrizAlocacoes.php";

// Verifica Permissão
if(!$usuario->temPermissao($MANTER_TURMAS)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}

$acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
if( $acao == NULL)
{
    $acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    if( $acao == NULL)
    {
        if( $siglaCursoFiltro !== "")
        {
            $acao = "informarPeriodoLetivo";
        }
        else
        {
            $acao = "informarCurso";
        }        
    }
}

switch( $acao) 
{
    case 'informarCurso':
        
        $listaDeCursos = Curso::obterCursosOrdemPorSigla();
        $estaDesabilitado = 'disabled'; //Desabilita os outros campos
        $listaDeTurnos = array('TODOS','MANHÃ','TARDE','NOITE');
        
        include "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_buscarTurmas.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
    case 'informarPeriodoLetivo':
        $listaDeCursos = Curso::obterCursosOrdemPorSigla();
        if( $siglaCursoFiltro === "")
        {
            $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
        }
        else
        {
            $siglaCurso = $siglaCursoFiltro;
        }
        
        $listaDePeriodosLetivos = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($siglaCurso);
        $listaDeTurnos = array('TODOS','MANHÃ','TARDE','NOITE');

        include "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_buscarTurmas.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
    case 'informarTurno':
        $listaDeCursos = Curso::obterCursosOrdemPorSigla();
        $siglaCurso = $_POST['siglaCurso'];
        $listaDePeriodosLetivos = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($siglaCurso);
        $listaDeTurnos = array('TODOS','MANHÃ','TARDE','NOITE');

        include "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_buscarTurmas.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;

    case 'exibirTurmas':
        
        exibirTurmas();
        break;
    case 'consultarTurma':

        $dadosDaTurma = consultarTurma($_POST['idTurma']);

        $siglaCurso = $dadosDaTurma['siglaCurso'];
        $matrizDeAlocacoes = obterMatrizAlocacoes($siglaCurso, $dadosDaTurma['idPeriodoLetivo'], $dadosDaTurma['periodo'], $dadosDaTurma['turno'], $dadosDaTurma['gradeHorario']);

        include "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_consultarTurma.php";
        require_once "$BASE_DIR/includes/rodape.php";

        registrarLog($dadosDaTurma['siglaCurso'], $dadosDaTurma['nomeCurso'], $dadosDaTurma['siglaPeriodoLetivo'], $dadosDaTurma['siglaDisciplina'], $dadosDaTurma['nomeDisciplina'], $dadosDaTurma['turno'], $dadosDaTurma['gradeHorario'], $dadosDaTurma['tipoSituacaoTurma']);
        break;
    
    case 'devolverPautaAoProfessor':
        
        $idTurma = $_POST['idTurma'];
        $turma = Turma::getTurmaById($idTurma);
        
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            
            $turma->devolverPautaAoProfessor();
            
            // Enviar mensagem ao professor avisando da devolução
            $corpo = sprintf("A secretaria acadêmica devolveu a pauta da disciplina %s, turno %s, grade %s, período letivo %s, 
                para retificações.",
                    $turma->getComponenteCurricular()->getSiglaDisciplina(),
                    $turma->getTurno(),
                    $turma->getGradeHorario(),
                    $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo() );
            $arrIdPessoa = array();
            $arrIdPessoa[] = $turma->getProfessor()->getIdPessoa();
            Mensagem::depositarMensagem("Pauta Devolvida", $corpo, $arrIdPessoa, $con);

            // Log de auditoria
            global $DEVOLVER_PAUTA_TURMA;
            $strLog = sprintf("Devolvida a pauta eletrônica da disciplina %s, turno %s, grade %s, período letivo %s, ao professor %s",
                    $turma->getComponenteCurricular()->getSiglaDisciplina(),
                    $turma->getTurno(),
                    $turma->getGradeHorario(),
                    $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(),
                    $turma->getProfessor()->getNome() );
            $usuario->incluirLog($LIBERAR_PAUTA_TURMA,  $strLog, $con);

            mysql_query("COMMIT", $con);

            $msgs = array();
            $msgs[] = "Turma devolvida ao professor com sucesso!";

        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgs = array();
            $msgs[] = "Erro ao devolver turma ao professor: " . $ex->getMessage();

        }
        
        exibirTurmas();
        break;

    default:
        trigger_error("Nao foi possivel identificar acao do controlador", E_USER_ERROR);
        break;
}

function buscarTurmas($siglaCurso,$idPeriodoLetivo,$turno) {

    $con = BD::conectar();

    $filtroPeriodoLetivo = sprintf(" AND PL.`idPeriodoLetivo` = %s ", mysql_real_escape_string($idPeriodoLetivo));

    if(strtolower($turno) != 'todos'){
        $filtroTurno = sprintf(" AND T.`turno` = UPPER('%s') ", mysql_real_escape_string($turno));
    }

    $collection = array ();

    $query =
        'SELECT DISTINCT T.`idTurma` , T.`siglaDisciplina` , CC.`nomeDisciplina`, P.`nome` as `nomeProfessor` ,  '
        . 'T.`turno`, T.`gradeHorario`, T.`tipoSituacaoTurma` , PL.`siglaPeriodoLetivo`,T.`dataLiberacaoPautaPeloProfessor` '
        . 'FROM `ComponenteCurricular` CC, `PeriodoLetivo` PL, `Turma` T '
        . 'left join `MatriculaProfessor` MP '
        . 'on MP.`matriculaProfessor` = T.`matriculaProfessor` '
        . 'left join `Pessoa` P '
        . 'on MP.`idPessoa` = P.`idPessoa` '
        . 'WHERE PL.`idPeriodoLetivo` = T.`idPeriodoLetivo` '
        . 'AND CC.`siglaCurso` = T.`siglaCurso` '
        . 'AND CC.`idMatriz` = T.`idMatriz` '
        . 'AND CC.`siglaDisciplina` = T.`siglaDisciplina` '
        . 'AND T.`tipoSituacaoTurma` != \'CANCELADA\' ' //T.`tipoSituacaoTurma`
        . $filtroPeriodoLetivo . chr(10)
        . $filtroTurno . chr(10)
        . ' AND T.`siglaCurso` = \'' . $siglaCurso . '\'' . chr(10)
        . 'ORDER BY T.`tipoSituacaoTurma`, T.`turno`, T.`gradeHorario`, T.`siglaDisciplina` ASC';

    $result = mysql_query($query);

    while ($infoTurma = mysql_fetch_array($result)) {
        array_push($collection, $infoTurma);
    }

    return $collection;
}

function buscarDisciplinasNaoOfertadas($siglaCurso,$idPeriodoLetivo, $turno) {

    $con = BD::conectar();

    $collection = array ();

    $query = sprintf("SELECT 
            CC.periodo,
            CC.`siglaDisciplina`,
            CC.`nomeDisciplina`
        FROM
        ComponenteCurricular CC
        left outer join
        Turma TUR
        ON 
        CC.`siglaDisciplina` = TUR.`siglaDisciplina`
        and TUR.siglaCurso = CC.siglaCurso 
        and TUR.idPeriodoLetivo = %d
        and TUR.turno = '%s'
        and TUR.tipoSituacaoTurma != 'CANCELADA'
        where
        isnull(TUR.`idTurma`)
        and CC.`idMatriz` in (
            select mc.`idMatriz` from MatrizCurricular mc
            where mc.siglaCurso = '%s'
            and mc.`dataInicioVigencia` in
            (
                SELECT max(mc_aux.`dataInicioVigencia`) FROM MatrizCurricular mc_aux, PeriodoLetivo pl_aux
                where mc_aux.siglaCurso = mc.siglaCurso
                and mc_aux.`dataInicioVigencia` <= pl_aux.`dataInicio`
                and pl_aux.`idPeriodoLetivo` = %d
            )
        )
        and CC.`siglaCurso` = '%s' 
        ORDER BY CC.periodo, CC.siglaDisciplina", 
            mysql_real_escape_string($idPeriodoLetivo),
            mysql_real_escape_string(strtoupper($turno)),
            mysql_real_escape_string($siglaCurso),
            mysql_real_escape_string($idPeriodoLetivo),
            mysql_real_escape_string($siglaCurso)
    );

    $result = mysql_query($query);

    while ($disciplina = mysql_fetch_array($result)) {
        array_push($collection, $disciplina);
    }

    return $collection;
}

function consultarTurma($idTurma) {

    $con = BD::conectar();

    $query = sprintf(
        'SELECT T.`idTurma` , T.`siglaDisciplina` , CC.`nomeDisciplina`, P.`nome` as `nomeProfessor`,  '
        . 'T.`turno`, T.`gradeHorario`, T.`tipoSituacaoTurma` , PL.`siglaPeriodoLetivo`, '
        . 'CUR.`siglaCurso`, CUR.`nomeCurso`, MC.`dataInicioVigencia`, CC.`tipoComponenteCurricular`, '
        . 'CC.`creditos`, CC.`cargaHoraria`, CC.`periodo`, T.`qtdeTotal`, T.`idPeriodoLetivo` '
        . 'FROM `Curso` CUR, `MatrizCurricular` MC, `ComponenteCurricular` CC, `PeriodoLetivo` PL, '
        . '`Turma` T '
        . 'left join `MatriculaProfessor` MP '
        . 'on MP.`matriculaProfessor` = T.`matriculaProfessor` '
        . 'left join `Pessoa` P '
        . 'on MP.`idPessoa` = P.`idPessoa` '
        . 'WHERE T.`idTurma` = %d '
        . 'AND MC.`siglaCurso` = T.`siglaCurso` '
        . 'AND MC.`idMatriz` = T.`idMatriz` '
        . 'AND PL.`idPeriodoLetivo` = T.`idPeriodoLetivo` '
        . 'AND CUR.`siglaCurso` = T.`siglaCurso` '
        . 'AND CC.`siglaCurso` = T.`siglaCurso` '
        . 'AND CC.`idMatriz` = T.`idMatriz` '
        . 'AND CC.`siglaDisciplina` = T.`siglaDisciplina` '
        . 'ORDER BY T.`tipoSituacaoTurma`, T.`turno`, T.`gradeHorario`, T.`siglaDisciplina` ASC'
        ,  mysql_real_escape_string($idTurma));

    $result = mysql_query($query);

    $infoTurma = mysql_fetch_array($result);
    
    return $infoTurma;
}

function registrarLog($siglaCurso, $nomeCurso, $siglaPeiodoLetivo, $siglaDisciplina, $nomeDisciplina, $turno, $grade, $situacao) {
    $mensagem = "Consultada turma do curso $siglaCurso($nomeCurso), Período Letivo $siglaPeiodoLetivo, ";
    $mensagem .= "disciplina $siglaDisciplina - $nomeDisciplina, Turno $turno, Grade $grade, situação $situacao";

    $_SESSION["usuario"]->incluirLog('UC01.03.00', $mensagem);
}

function exibirTurmas() {
    $listaDeCursos = Curso::obterCursosOrdemPorSigla();
    $siglaCurso = $_REQUEST['siglaCurso'];
    $idPeriodoLetivo = $_REQUEST['idPeriodoLetivo'];
    $turno = strtoupper($_REQUEST['turno']);
    $listaDePeriodosLetivos = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($siglaCurso);
    $listaDeTurnos = array('TODOS','MANHÃ','TARDE','NOITE');
    
    //Monta um array com as turmas encontradas
    $arrayDeExibicaoDeTurmas = buscarTurmas($siglaCurso,$idPeriodoLetivo,$turno);

    //Caso o usuário tenha selecionado um turno específico
    if(strtolower($turno) != 'todos'){
        //Monta um array com as turmas ainda não oferecidas para aquele turno
        $arrayDeTurmasNaoOfertadas = buscarDisciplinasNaoOfertadas($siglaCurso, $idPeriodoLetivo, $turno);
    }

    global $BASE_DIR;
    global $RAIZ_CORUJA;
    global $msgs;
    include "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_buscarTurmas.php";
    $turno = strtoupper($_POST['turno']); //Recupero o valor horiginal de $turno, o include anterior altera o valor de $turno
    include "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_exibirTurmas.php";
    include "$BASE_DIR/includes/rodape.php";
}
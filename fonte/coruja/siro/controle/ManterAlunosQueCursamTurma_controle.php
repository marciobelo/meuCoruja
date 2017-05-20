<?php
require_once("../../includes/comum.php");
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Aloca.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Mensagem.php";
require_once "$BASE_DIR/siro/classes/buscaAluno.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/siro/classes/formulario.class.php";

// Recupera o usuario logado da sessao
$usuario = $_SESSION["usuario"];

$act = filter_input( INPUT_GET, "act", FILTER_SANITIZE_STRING);
$acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
$passo = filter_input( INPUT_POST, "passo", FILTER_SANITIZE_STRING);

// TOPO DA PÁGINA
require_once "$BASE_DIR/includes/topo.php";

include_once "$BASE_DIR/includes/menu_horizontal.php";

// CONTEUDO DA PAGINA - ARQUIVO QUE TEM A FUNCAO DE TRATAR AS REQUISIÇOES
echo '<div id="conteudo">';
    
$formulario = new formulario();
$classeInscricao = new Inscricao();
$classeTurma = new Turma();
$classeComponenteCurricular = new ComponenteCurricular();
$classeMatAluno = new MatriculaAluno();
$classeAluno = new Aluno();
$classeCurso = new Curso();

if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}

if( $act === "main") // acao para exibir a pagina de filtro de curso
{ 
    // Verifica Permissao
    if(!$usuario->temPermissao($MANTER_ALUNOS_QUE_CURSAM_TURMA)) {
            require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
            exit;
    }
    $collection = Curso::obterCursosOrdemPorSigla();
    require "$BASE_DIR/siro/formularios/manterAlunoQueCursaListarTurma.php";

} elseif($acao === "verTurmas") {

    $siglaCurso = $_POST['siglaCurso'];
    $idPeriodoLetivo = $_POST['idPeriodoLetivo'];
    $turno = $_POST['turno'];

    if($siglaCurso ==="" || $idPeriodoLetivo === "" || $turno === "") 
    {
        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!!!</font>";
        echo "</fieldset></form>";

        $collection = Curso::obterCursosOrdemPorSigla();
        require "$BASE_DIR/siro/formularios/manterAlunoQueCursaListarTurma.php";

    } else {

        $perLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodoLetivo);
        $busca = "siglaCurso='$siglaCurso' AND idPeriodoLetivo='$idPeriodoLetivo' AND turno='$turno' AND tipoSituacaoTurma in ('LIBERADA','CONFIRMADA')";

        $listaTurmas = $classeTurma->obterTurmas($busca);

        $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
        $nomeCurso = $classeCurso->getNomeCurso();

        require "$BASE_DIR/siro/formularios/manterAlunosQueCursamTurma.php";
    }

} elseif($acao == "verAlunosTurma") {

    $idTurma = $_POST['idTurma'];

    $detalhesTurma = $classeTurma->getTurmaById($idTurma);

    $siglaDisciplina = $detalhesTurma->getSiglaDisciplina();

    $detalhesDisciplina = ComponenteCurricular::obterComponenteCurricular($detalhesTurma->getSiglaCurso(),$detalhesTurma->getIdMatriz(),$detalhesTurma->getSiglaDisciplina());

    $nomeDisciplina = $detalhesDisciplina->getNomeDisciplina();

    $listaAlunosTurma = $classeInscricao->listaAlunosTurma($idTurma);

    $perLetivo = PeriodoLetivo::obterPeriodoLetivo($detalhesTurma->getIdPeriodoLetivo());

    $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
    $nomeCurso = $classeCurso->getNomeCurso();

    require "$BASE_DIR/siro/formularios/excluirAlunoQueCursa.php";

} elseif($acao == "excluirAlunoQueCursa") {

    $idTurma = $_POST['idTurma'];
    $matriculaAluno = $_POST['matriculaAluno'];

    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação
        Inscricao::excluir($idTurma, $matriculaAluno,
                "Excluído pela secretaria ou coordenação.", $con);

        global $MANTER_ALUNOS_QUE_CURSAM_TURMA;
        $aluno = Aluno::getAlunoByNumMatricula($matriculaAluno);
        $turma = Turma::getTurmaById($idTurma);
        $componenteCurricular = $turma->getComponenteCurricular();
        $periodoLetivo = $turma->getPeriodoLetivo();
        $curso = $turma->getCurso();
        $strLog = "Aluno excluído de turma: " . $aluno->getNome() . ", de matrícula " .
                $matriculaAluno . ", da turma de " . $turma->getSiglaDisciplina() .
                " (" . $componenteCurricular->getNomeDisciplina() . "), " .
                "Período Letivo " . $periodoLetivo->getSiglaPeriodoLetivo() . ", turno " .
                $turma->getTurno() . ", grade " . $turma->getGradeHorario() . ", " .
                "do Curso " . $curso->getSiglaCurso() . " (" . $curso->getNomeCurso() . ")";
        $usuario->incluirLog($MANTER_ALUNOS_QUE_CURSAM_TURMA, $strLog, $con);

        // Avisa ao professor aluno excluído da pauta
        if( $turma->isNotificaProfessorMudancaPauta() ) {
            $arrIdPessoa = array();
            $arrIdPessoa[] = $turma->getProfessor()->getIdPessoa();
            Mensagem::depositarMensagem("Aluno excluído de pauta", $strLog, $arrIdPessoa, $con);
        }

        mysql_query("COMMIT", $con);
    } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            trigger_error("Erro ao excluir aluno que cursa turma: " . $ex->getMessage(), E_USER_ERROR);
    }

    // Prepara visão
    $detalhesTurma = $classeTurma->getTurmaById($idTurma);
    $siglaDisciplina = $detalhesTurma->getSiglaDisciplina();
    $detalhesDisciplina = ComponenteCurricular::obterComponenteCurricular($detalhesTurma->getSiglaCurso(),$detalhesTurma->getIdMatriz(),$detalhesTurma->getSiglaDisciplina());
    $nomeDisciplina = $detalhesDisciplina->getNomeDisciplina();
    $listaAlunosTurma = $classeInscricao->listaAlunosTurma($idTurma);
    $perLetivo = PeriodoLetivo::obterPeriodoLetivo($detalhesTurma->getIdPeriodoLetivo());
    $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
    $nomeCurso = $classeCurso->getNomeCurso();

    echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
    echo "<font>Aluno exclu&iacute;do com sucesso!</font>";
    echo "</fieldset></form>";

    require "$BASE_DIR/siro/formularios/excluirAlunoQueCursa.php";

} elseif($acao == "formBusca") {

    $idTurma = $_POST['idTurma'];
    $siglaDisciplina = $_POST['siglaDisciplina'];
    $nomeDisciplina = $_POST['nomeDisciplina'];

    require "$BASE_DIR/siro/formularios/incluirAlunoQueCursaFormBusca.php";

} elseif($acao == "buscarAluno") {

    $idTurma = $_POST['idTurma'];
    $tipoBusca = $_POST['tipoBusca'];
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];

    $classeBuscaAluno = new buscaAluno();

    $siglaDisciplina = $_POST['siglaDisciplina'];

    $nomeDisciplina = $_POST['nomeDisciplina'];

    if($tipoBusca == "nome"){
        $resultadoBusca = $classeBuscaAluno->buscaAlunoByNome($nome);
    }
    elseif($tipoBusca == "matricula"){
        $resultadoBusca = $classeBuscaAluno->buscaAlunoByMatricula($matricula);
    }

    if(empty($resultadoBusca)){//EXIBE A MENSAGEM E CONTINUA NA MESMA TELA

            echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
            echo "<font>Nenhum aluno encontrado com os par&acirc;metros informados!</font>";
            echo "</fieldset></form>";

            require "$BASE_DIR/siro/formularios/incluirAlunoQueCursaFormBusca.php";

    }
    else {
        require "$BASE_DIR/siro/formularios/incluirAlunoQueCursaBuscarAluno.php";
    }

} elseif($acao == "incluirAlunoQueCursa") {
    $matriculaAluno = $_POST['matricula'];
    $idTurma = $_POST['idTurma'];

    $inscricao = new Inscricao();

    //necessario para preencher o cabecalho da pagina com as informacoes selecionadas pelo usuario
    $detalhesTurma = $classeTurma->getTurmaById($idTurma);

    $siglaDisciplina = $detalhesTurma->getSiglaDisciplina();

    $detalhesDisciplina = ComponenteCurricular::obterComponenteCurricular($detalhesTurma->getSiglaCurso(),$detalhesTurma->getIdMatriz(),$detalhesTurma->getSiglaDisciplina());

    $nomeDisciplina = $detalhesDisciplina->getNomeDisciplina();

    $listaAlunosTurma = $classeInscricao->listaAlunosTurma($idTurma);

    $perLetivo = PeriodoLetivo::obterPeriodoLetivo($detalhesTurma->getIdPeriodoLetivo());

    $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
    $nomeCurso = $classeCurso->getNomeCurso();

    $turma = Turma::getTurmaById($idTurma);
    $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);

    // VERIFICA SE O ALUNO CUMPRIU O PRÉ-REQUISITO;
    $quitacaoTO = $detalhesDisciplina->obterQuitacao($ma);

    if($quitacaoTO != null){
        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>N&atilde;o foi poss&iacute;vel incluir o aluno na turma. Esta matr&iacute;cula j&aacute; cumpriu o requisito (ou foi isento).</font>";
        echo "</fieldset></form>";
    } else if(!$classeMatAluno->verificaSituacaoMatriculaAluno($matriculaAluno,'CURSANDO')){
        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>Não foi possível incluir o aluno na turma. O mesmo encontra-se com a situaçao da matricula diferente de cursando.</font>";
        echo "</fieldset></form>";
    }
    /* VERIFICA SE O ALUNO ESTÁ CURSANDO OU ESTÃO ISENTO DA DISCIPLINA */
    else if( Inscricao::alunoJaInscritoMesmoComponente($ma, $turma) ) {

        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>Não foi possível incluir o aluno na turma. O mesmo já está cursando o componente nesta ou em outra turma.</font>";
        echo "</fieldset></form>";
    }
    /*VERIFICA SE HA CONFLITO DE HORARIO ENTRE A TURMA QUE O ALUNO SERA
     *  INCLUIDO COM AS TURMAS QUE ELE JA ESTA CURSANDO */
    else if( Inscricao::verificaConflito($idTurma, $matriculaAluno,'CUR')){

        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>O aluno não pode se inscrever na disciplina por conflito de horário</font>";
        echo "</fieldset></form>";
    } else { //SE NAO HOUVER NENHUM IMPEDIMENTO INSERE O ALUNO

        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            Inscricao::registrarCursando($idTurma, $matriculaAluno,
                    "Aluno inserido pela secretaria acadêmica ou coordenação do curso.");

            global $INCLUIR_ALUNOS_QUE_CURSAM_TURMA;
            $aluno = Aluno::getAlunoByNumMatricula($matriculaAluno);
            $componenteCurricular = $turma->getComponenteCurricular();
            $periodoLetivo = $turma->getPeriodoLetivo();
            $curso = $turma->getCurso();
            $strLog = "Aluno incluído na turma: " . $aluno->getNome() . ", de matricula " .
                    $matriculaAluno . ", na turma de " . $turma->getSiglaDisciplina() .
                    " (" . $componenteCurricular->getNomeDisciplina() . "), " .
                    "Período Letivo " . $periodoLetivo->getSiglaPeriodoLetivo() . ", turno " .
                    $turma->getTurno() . ", grade " . $turma->getGradeHorario() . ", " .
                    "do Curso " . $curso->getSiglaCurso() . " (" . $curso->getNomeCurso() . ")";
            $usuario->incluirLog($INCLUIR_ALUNOS_QUE_CURSAM_TURMA, $strLog, $con);

            // Avisa ao professor aluno incluído na pauta
            if( $turma->isNotificaProfessorMudancaPauta() ) {
                $arrIdPessoa = array();
                $arrIdPessoa[] = $turma->getProfessor()->getIdPessoa();
                Mensagem::depositarMensagem("Aluno incluído em pauta", $strLog, $arrIdPessoa, $con);
            }
            mysql_query("COMMIT", $con);

        } catch(Exception $ex) {
                mysql_query("ROLLBACK", $con);
                trigger_error("Erro ao inserir aluno cursando turma: " . $ex->getMessage(), E_USER_ERROR);
        }
        echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
        echo "<font>Aluno Inclu&iacute;do na Turma com Sucesso!</font>";
        echo "</fieldset></form>";
    }

    //necessario para preencher o cabecalho da pagina com as informacoes selecionadas pelo usuario
    $detalhesTurma = $classeTurma->getTurmaById($idTurma);
    $siglaDisciplina = $detalhesTurma->getSiglaDisciplina();
    $detalhesDisciplina = ComponenteCurricular::obterComponenteCurricular($detalhesTurma->getSiglaCurso(),$detalhesTurma->getIdMatriz(),$detalhesTurma->getSiglaDisciplina());
    $nomeDisciplina = $detalhesDisciplina->getNomeDisciplina();
    $listaAlunosTurma = $classeInscricao->listaAlunosTurma($idTurma);
    $perLetivo = PeriodoLetivo::obterPeriodoLetivo($detalhesTurma->getIdPeriodoLetivo());
    $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
    $nomeCurso = $classeCurso->getNomeCurso();

    require "$BASE_DIR/siro/formularios/excluirAlunoQueCursa.php";
}
echo '</div>';

// RODAPÉ DA PÁGINA
include_once "$BASE_DIR/includes/rodape.php";
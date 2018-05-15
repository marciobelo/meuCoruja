<?php
// REQUIRE DO ARQUIVO COMUM
require_once("../../includes/comum.php");
include_once "$BASE_DIR/classes/Inscricao.php";
include_once "$BASE_DIR/classes/ComponenteCurricular.php";
include_once "$BASE_DIR/classes/Aloca.php";
include_once "$BASE_DIR/classes/Aluno.php";
include_once "$BASE_DIR/classes/Turma.php";
include_once "$BASE_DIR/classes/MatriculaAluno.php";
include_once "$BASE_DIR/classes/TempoSemanal.php";
include_once "$BASE_DIR/classes/PeriodoLetivo.php";
include_once "$BASE_DIR/classes/EventoPeriodoLetivo.php";
include_once "$BASE_DIR/siro/classes/funcoesRN.php";

// INCLUDE DA CLASSE CURSO
include_once "$BASE_DIR/classes/Curso.php";
include_once "$BASE_DIR/classes/Aluno.php";

// TOPO DA P�GINA
include_once "$BASE_DIR/includes/topo.php";

// MENU HORIZONTAL
echo '<div id="menuprincipal">';
include_once "$BASE_DIR/includes/menu_horizontal.php";
echo '</div>';

// Se n�o � o pr�prio aluno, verifica se tem permiss�o para excluir solicita��o
$matriculaAluno = null;
if(!$login->isAluno()) {
    // Verifica Permissao
    if(!$login->temPermissao($EXCLUIR_SOLICITACAO_INSCRICAO_TURMA)) {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        exit;
    }
    $matriculaAluno = MatriculaAluno::obterMatriculaAluno($_SESSION['matrAlunoSelec']);
} else {
    $matriculaAluno = MatriculaAluno::obterMatriculaAluno($login->getNomeAcesso());
}
$idPessoa = $matriculaAluno->getIdPessoa();

//CONTEUDO
echo '<div id="conteudo">';
$action = $_POST['acao'];

if($action == "excluirSolicitacao") {

    $turmaSolicitada = $_POST['idTurma'];
    
    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transa��o

        Inscricao::excluir($turmaSolicitada, $matriculaAluno->getMatriculaAluno(),
                "Exclu�do no per�odo de inclus�o/exclus�o de turmas.", $con);
        
        $aluno = Aluno::getAlunoByIdPessoa($matriculaAluno->getIdPessoa());
        $turma = Turma::getTurmaById($turmaSolicitada);
        $componenteCurricular = ComponenteCurricular::obterComponenteCurricular(
                $turma->getSiglaCurso(), $turma->getIdMatriz(), $turma->getSiglaDisciplina() );
        $periodoLetivo = $turma->getPeriodoLetivo();
        $curso = $turma->getCurso();

        $strLog = "Foi exclu�da a solicita��o de inscri��o do aluno ". $aluno->getNome() .
            ", de matr�cula " . $matriculaAluno->getMatriculaAluno() . " " .
            " na turma de ". $turma->getSiglaDisciplina() . " (" .
            $componenteCurricular->getNomeDisciplina() . "), " .
            "Per�odo Letivo ".$periodoLetivo->getSiglaPeriodoLetivo() . ", turno " .
            $turma->getTurno() . " grade " . $turma->getGradeHorario() . " " .
            "do Curso " . $curso->getSiglaCurso() . " (" . $curso->getNomeCurso() . ")";
        $login->incluirLog($EXCLUIR_SOLICITACAO_INSCRICAO_TURMA,$strLog,$con);

        mysql_query("COMMIT", $con);

        echo "<html><head><title>Coruja</title></head><body>";
        echo "<p style=\"text-align: center; font-size: larger\">Solicita&ccedil;&atilde;o de inscri&ccedil;&atilde;o exclu&iacute;da com sucesso!</p>";
        echo"<form id='listar' name='listar' action='SolicitarInscricaoEmTurmas_controle.php?action=listar' method='post'>";
        echo"<input type='hidden' name='idPessoa' value='$idPessoa'>";
        echo"<script>document.listar.submit();</script></form>";
        echo"</body></html>";

    } catch (Exception $ex) {
        mysql_query("ROLLBACK", $con);
        trigger_error("Erro ao excluir solicita��o de inscri��o com a messagem: " . $ex->getMessage() ,E_USER_ERROR);
        exit;
    }

}
echo '</div>';

// RODAP� DA P�GINA
include_once "$BASE_DIR/includes/rodape.php";
?>
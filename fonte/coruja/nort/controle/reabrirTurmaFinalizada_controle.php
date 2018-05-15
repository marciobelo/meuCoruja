<?php
/* UC01.03.04 - Reabrir Turma Finalizada */
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Professor.php";

// Verifica Permissão
if(!$login->temPermissao($REABRIR_TURMA_FINALIZADA)) {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        exit();
}

$acao = $_POST["acao"];

$idTurma = $_POST["idTurma"];
$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();
$siglaCurso = $turma->getCurso()->getSiglaCurso();
$idPeriodoLetivo = $turma->getIdPeriodoLetivo();
$turno = $_POST["turno"];

$turma->getPeriodoLetivo()->getSiglaPeriodoLetivo();
$turma->getCurso()->getSiglaCurso();
$turma->getCurso()->getNomeCurso();

switch($acao) {
    case "exibirConfirmaReabrirTurmaFinalizada":
        require "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_confirmaReabrirTurmaFinalizada.php";

        break;

    case "reabrirTurmaFinalizada":
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $turma->reabrirTurma($con);
            $strLog = sprintf("Reaberta a turma %s (%s), grade %s,
                período letivo %s, lecionada pelo professor(a) %s,
                do curso %s (%s).",
                    $turma->getSiglaDisciplina(),
                    $turma->getComponenteCurricular()->getNomeDisciplina(),
                    $turma->getGradeHorario(),
                    $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(),
                    ($turma->getProfessor() != null ? $turma->getProfessor()->getNome() : "<não informado>" ),
                    $turma->getCurso()->getSiglaCurso(),
                    $turma->getCurso()->getNomeCurso());
            $login->incluirLog($REABRIR_TURMA_FINALIZADA,$strLog,$con);
            mysql_query("COMMIT", $con);
            Header("location: /coruja/nort/controle/manterTurmas_controle.php?acao=exibirTurmas&siglaCurso=$siglaCurso&idPeriodoLetivo=$idPeriodoLetivo&turno=$turno");
            
        } catch (Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msg = $ex->getMessage();
            require "$BASE_DIR/nort/formularios/manterTurmas/manterTurmas_confirmaReabrirTurmaFinalizada.php";
            exit;
        }
        break;
    case "voltar":
        Header("location: /coruja/nort/controle/manterTurmas_controle.php?acao=exibirTurmas&siglaCurso=$siglaCurso&idPeriodoLetivo=$idPeriodoLetivo&turno=$turno");
        break;
    default:
        trigger_error("Não foi possível identificar $acao.", E_USER_ERROR);
        break;
}
?>
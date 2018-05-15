<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/PeriodoLetivo.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/DiaLetivoTurma.php");
require_once("$BASE_DIR/classes/Util.php");

$acao = $_REQUEST["acao"];
$idTurma = $_REQUEST["idTurma"];

$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();
$dtInicio = Util::converteDateTime( $turma->getPeriodoLetivo()->getDataInicio() );
$dtFim = Util::converteDateTime( $turma->getPeriodoLetivo()->getDataFim() );

// Verifica se o professor é o titular da turma informada
if( $professor->getIdPessoa() != $login->getIdPessoa() ) {
    trigger_error("Usuário não tem permissão para executar essa ação!",E_USER_ERROR);
    exit;
}

if( !isset ($acao) ) {

    if( isset ($_SESSION["msgsErro"]) ) {
        $msgsErro = $_SESSION["msgsErro"];
        unset ($_SESSION["msgsErro"]);
    }    
        
    $data = $_GET["data"];
    if( isset($data) ) {
        $dataDiaLetivoTurma = new DateTime($data);
        $diaLetivoTurma = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivoTurma);
        $strDiaSemana = Util::gerarSiglaDiaSemana($dataDiaLetivoTurma->format("w"));
        $temposDiaSemana = TempoSemanal::obterListaTempoSemanalPorDiaSemana($turma->getSiglaCurso(),
                $strDiaSemana);
        $temposSelecionados = $diaLetivoTurma->getListaTempoSemanal();

        $dataBr = $dataDiaLetivoTurma->format("d/m/Y");
    }

    include "$BASE_DIR/espacoProfessor/pautaEletronica/formNovoDiaLetivo.php";
    exit;

} else {
    switch($acao) {
    case "carregarTemposAula":

        $dataBr = $_REQUEST["dataBr"];
        try {
            $data = Util::converteDataBrParaDateTime($dataBr);
            $idTurma = $turma->getIdTurma();
            $strData = $data->format("Y-m-d");
        } catch(Exception $e) {
            $msgErro = array();
            $msgsErro[] = sprintf("Data %s não é válida.", $dataBr);
            $_SESSION["msgsErro"] = $msgsErro;
        }
        Header("Location: /coruja/espacoProfessor/pautaEletronica/criarNovoDiaLetivo_controle.php?idTurma=$idTurma&data=$strData");
        exit;

    case "salvarDiaLetivo":

        $data = $_POST["data"];
        $listaIdTempoSemanal = $_POST["listaIdTempoSemanal"];
        $dataDiaLetivoTurma = new DateTime($data);
        $diaLetivoTurma = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivoTurma);

        // Validação se não selecionar nenhum tempo de aula
        if( count($listaIdTempoSemanal) == 0 ) {
            $idTurma = $turma->getIdTurma();
            $strData = $dataDiaLetivoTurma->format("Y-m-d");
            $msgsErro = array();
            $msgsErro[] = "Erro: dia letivo deve ter ao menos um tempo selecionado.";
            $_SESSION["msgsErro"] = $msgsErro;
            Header("Location: /coruja/espacoProfessor/pautaEletronica/criarNovoDiaLetivo_controle.php?idTurma=$idTurma&data=$strData");
            exit;
        }
        
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $strLog = sprintf("Alterado o dia letivo %s da turma %s, grade %s, período letivo %s,
                curso %s<br/>",
                    $dataDiaLetivoTurma->format("d/m/Y"),
                    $turma->getSiglaDisciplina(),
                    $turma->getGradeHorario(),
                    $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(),
                    $turma->getSiglaCurso());
            $listaTempoSemanalAnterior = $diaLetivoTurma->getListaTempoSemanal();
            if( count($listaTempoSemanalAnterior) == 0 ) {
                $strLog .= "inexistente";
            } else {
                $i = 1;
                $strLog .= "Tempo de aula antes:<br/>";
                foreach($listaTempoSemanalAnterior as $tempoSemanal) {
                    $strLog .= sprintf("%do.tempo: %s - %s<br/>",
                            $i,
                            $tempoSemanal->getHoraInicio(),
                            $tempoSemanal->getHoraFim());
                    $i++;
                }
            }
            $diaLetivoTurma->alterarTempos( $listaIdTempoSemanal, $con );
            $listaTempoSemanalAtual = $diaLetivoTurma->getListaTempoSemanal();
            $i = 1;
            $strLog .= "Tempo de aula depois:<br/>";
            foreach($listaTempoSemanalAtual as $tempoSemanal) {
                $strLog .= sprintf("%do.tempo: %s - %s<br/>",
                        $i,
                        $tempoSemanal->getHoraInicio(),
                        $tempoSemanal->getHoraFim());
                $i++;
            }

            global $ALTERAR_DIA_LETIVO_TURMA;
            $login->incluirLog($ALTERAR_DIA_LETIVO_TURMA,  $strLog, $con);
            mysql_query("COMMIT", $con);

            $msgsErro = array();
            $msgsErro[] = "Tempos do dia letivo salvos com sucesso.";
            $_SESSION["msgsErro"] = $msgsErro;
        } catch(Exception $e) {
            $msgsErro = array();
            $msgsErro[] = "Erro ao alterar dia letivo.";
            $_SESSION["msgsErro"] = $msgsErro;
            mysql_query("ROLLBACK", $con);
        }
        $idTurma = $turma->getIdTurma();
        $strData = $dataDiaLetivoTurma->format("Y-m-d");
        Header("Location: /coruja/espacoProfessor/pautaEletronica/criarNovoDiaLetivo_controle.php?idTurma=$idTurma&data=$strData");
        exit;
    }
}
?>

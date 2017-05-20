<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Usuario.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/PeriodoLetivo.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/DiaLetivoTurma.php");
require_once("$BASE_DIR/classes/Mensagem.php");

$acao = $_POST["acao"];
$idTurma = $_POST["idTurma"];
$numMatriculaAluno = $_POST["numMatriculaAluno"];
$data = $_POST["data"];
$stringValor = $_POST["stringValor"];
$usuario = $_SESSION["usuario"];

$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();

// Verifica se o professor é o titular da turma informada
if( $professor->getIdPessoa() != $usuario->getIdPessoa() ) {
    echo "Usuário não tem permissão para executar essa ação!";
    exit;
}

switch( $acao ) {
    
    case "apontarDiaLetivo":
        // Resposta à view formPaulaEletronica.php
        // ok sinaliza sucesso para a função de callback do ajax
        // erro: blah blah sinaliza mensagem a ser exibida
        // qualquer coisa diferente: erro não tratável
        try {
            $dataDiaLetivoTurma = new DateTime($data);
            $d = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivoTurma);
            if( !$d->estaPersistido() ) {
                $d->persisteDiaLetivoRegular();
            }
            $d->lancarApontaTempoAula( $numMatriculaAluno, $stringValor );
            echo "ok";
            exit;
        } catch(Exception $ex) {
            echo "erro: " . $ex->getMessage();
            exit;
        }
        break;
        
    case "apontarDiaLetivoConteudo":
        try {
            //  According to the jQuery.ajax() contentType documentation: 
            //  Data will always be transmitted to the server using UTF-8 charset; 
            //  you must decode this appropriately on the server side."
            $texto = utf8_decode($stringValor);

            $dataDiaLetivoTurma = new DateTime($data);
            $d = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivoTurma);
            if( !$d->estaPersistido() ) {
                $d->persisteDiaLetivoRegular();
            }
            $d->lancarApontaTempoAulaConteudo( $numMatriculaAluno, $texto );
            echo "ok";
            exit;
        } catch(Exception $ex) {
            echo "erro: " . $ex->getMessage();
            exit;
        }
        break;
        
    case "apontarDiaLetivoAnotacao":
        try {
            $texto = utf8_decode($stringValor); // ver comentário em apontarDiaLetivoConteudo
            
            $dataDiaLetivoTurma = new DateTime($data);
            $d = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivoTurma);
            if( !$d->estaPersistido() ) {
                $d->persisteDiaLetivoRegular();
            }
            $d->lancarApontaTempoAulaAnotacao( $numMatriculaAluno, $texto );
            echo "ok";
            exit;
        } catch(Exception $ex) {
            echo "erro: " . $ex->getMessage();
            exit;
        }
        break;

    case "liberarDiaLetivoTurma":
        
        $dataDiaLetivoTurma = new DateTime($data);
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $turma->liberarDiaLetivoTurma($dataDiaLetivoTurma);
            
            $professor = $turma->getProfessor();

            $strLog = sprintf("Apontado dia letivo de turma: %s<br/>
                Disciplina: %s - grade %s - %s - curso %s<br/>",
                    $dataDiaLetivoTurma->format("d/m/Y"),
                    $turma->getComponenteCurricular()->getSiglaDisciplina(),
                    $turma->getGradeHorario(),
                    $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(),
                    $turma->getCurso()->getSiglaCurso() );
            $diaLetivoTurma = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivoTurma);
            $inscricoes =  $turma->getInscricoesDePauta();
            foreach($inscricoes as $inscricao) {
                $resumo = $inscricao->obterResumoApontamentoDiaLetivo($diaLetivoTurma);
                $strLog .= sprintf("%s:%s ",
                        $inscricao->getMatriculaAluno(),
                        $resumo);
                $qtdeFaltas = substr_count($resumo, "F");
                if( $qtdeFaltas > 0  && $inscricao->atualizarResumoApontamentoDiaLetivo( $diaLetivoTurma, $resumo) ) {
                    // Mandar e-mail para o aluno
                    $corpo = sprintf("Registrada(s) %d falta(s) no dia %s, na turma da disciplina de %s, " .
                            "turno %s, grade %s, período letivo %s\n ".
                            "Você já tem %d faltas computadas.\n" .
                            "Fique atento para não ser reprovado por faltas. Acompanhe os lançamentos do seu professor(a) no diário.",
                            $qtdeFaltas,
                            $dataDiaLetivoTurma->format("d/m/Y"),
                            $turma->getComponenteCurricular()->getSiglaDisciplina(),
                            $turma->getTurno(),
                            $turma->getGradeHorario(),
                            $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(),
                            ($inscricao->obterFaltasLancadas()) );
                    $arrIdPessoa = array();
                    $matriculaAluno =  $inscricao->obterMatriculaAluno();
                    $arrIdPessoa[] = $matriculaAluno->getIdPessoa();
                    Mensagem::depositarMensagem("Falta", $corpo, $arrIdPessoa, $con);
                }
            }
            global $APONTAR_DIA_LETIVO_TURMA;
            $usuario->incluirLog($APONTAR_DIA_LETIVO_TURMA,  $strLog, $con);
            mysql_query("COMMIT", $con);
            
        } catch(Exception $exc) {
            mysql_query("ROLLBACK", $con);
            $col = array();
            $col[] = $exc->getMessage();
            $_SESSION["msgsErro"] = $col;
        }

        include_once "$BASE_DIR/espacoProfessor/pautaEletronica/formGerarDiario.php";
        break;

    case "reabrirDiaLetivoTurma":
        $dataDiaLetivoTurma = new DateTime($data);
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $turma->reabrirDiaLetivoTurma($dataDiaLetivoTurma, $con);

            $strLog = sprintf("Reaberto para correções de apontamento o dia letivo de turma: %s<br/>
                Disciplina: %s - grade %s - %s - curso %s<br/>",
                    $dataDiaLetivoTurma->format("d/m/Y"),
                    $turma->getComponenteCurricular()->getSiglaDisciplina(),
                    $turma->getGradeHorario(),
                    $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(),
                    $turma->getCurso()->getSiglaCurso() );
            global $REABRIR_DIA_LETIVO_TURMA;
            $usuario->incluirLog($REABRIR_DIA_LETIVO_TURMA,  $strLog, $con);
            
            mysql_query("COMMIT", $con);
            
        } catch(Exception $exc) {
            mysql_query("ROLLBACK", $con);
            $col = array();
            $col[] = $exc->getMessage();
            $_SESSION["msgsErro"] = $col;
        }

        Header("Location: /coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=" .
            $turma->getIdTurma() . "&data=" . $dataDiaLetivoTurma->format("Y-m-d"));
        break;
    
    case "emitirPauta":
        

}
?>

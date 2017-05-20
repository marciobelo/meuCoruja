<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Usuario.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/Mensagem.php");

$idTurma = $_REQUEST["idTurma"];
$acao = $_REQUEST["acao"];
$usuario = $_SESSION["usuario"];
$idItemCriterioAvaliacao = $_REQUEST["idItemCriterioAvaliacao"];

$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();

if( isset ($_SESSION["msgsErro"]) ) {
    $msgsErro = $_SESSION["msgsErro"];
    unset ($_SESSION["msgsErro"]);
}

// Verifica se o professor � o titular da turma informada
if( $professor->getIdPessoa() != $usuario->getIdPessoa() ) {
    $msgsErro = array();
    $msgsErro[] = "Voc� n�o est� autorizado a abrir a pauta eletr�nica dessa turma";
    $professorLogado = Professor::getProfessorByIdPessoa($usuario->getIdPessoa() );
    $turmas = Turma::obterTurmasConfirmadasPorProfessor( $professorLogado );
    include "$BASE_DIR/espacoProfessor/index.php";
}

$inscricoesDePauta = $turma->getInscricoesDePauta();
$criterioAvaliacao = $turma->getCriterioAvaliacao();
$itensCriterioAvaliacao = $criterioAvaliacao->getItensCriterioAvaliacao();

if( $acao == "exibirAvaliacaoLancarNotas" ) {
    
    $itemCriterioAvaliacao = ItemCriterioAvaliacao::obterPorId($idItemCriterioAvaliacao);
    include "$BASE_DIR/espacoProfessor/pautaEletronica/formPautaEletronicaAvaliacaoLancarNotas.php";
    
} else if( $acao == "liberarNotasItemCriterio" ) {
    
    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transa��o
        
        // Registra como liberado o item de crit�rio de avalia��o para uma turma
        $itemCriterioAvaliacao = ItemCriterioAvaliacao::obterPorId($idItemCriterioAvaliacao);
        $turma->liberarItemCriterioAvaliacao( $itemCriterioAvaliacao, $con );
        
        
        // Envia e-mail para todos os alunos dessa turma que tiveram a nota atualizada nessa libera��o
        $notasDaTurmaEmString = "";
        foreach($inscricoesDePauta as $inscricao) {
          
            $itensCriterioAvaliacaoNota = ItemCriterioAvaliacaoInscricaoNota::obterItensCriterioAvaliacaoInscricaoNota($inscricao);
            foreach($itensCriterioAvaliacaoNota as $itemCriterioAvaliacaoNota) {
                
                if($itemCriterioAvaliacaoNota->getItemCriterioAvaliacao() == $itemCriterioAvaliacao ) {
                    
                    if( $itemCriterioAvaliacaoNota->getDataNotificacao() == null && 
                            ( $itemCriterioAvaliacaoNota->getNota() != null ) ) {
                        // Mandar e-mail para o aluno
                        $corpo = sprintf("Prezado Aluno(a), \n" .
                                "Foi lan�ada a nota %s em %s na turma da disciplina de %s, " .
                                "turno %s, grade %s, per�odo letivo %s\n ".
                                "Acompanhe os lan�amentos do seu professor(a) no di�rio.",
                                $itemCriterioAvaliacaoNota->getNota(),
                                $itemCriterioAvaliacao->getRotulo() ,
                                $turma->getComponenteCurricular()->getSiglaDisciplina(),
                                $turma->getTurno(),
                                $turma->getGradeHorario(),
                                $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo() );
                        $arrIdPessoa = array();
                        $matriculaAluno =  $inscricao->obterMatriculaAluno();
                        $arrIdPessoa[] = $matriculaAluno->getIdPessoa();
                        Mensagem::depositarMensagem("Lan�amento de Nota", $corpo, $arrIdPessoa, $con);

                        $itemCriterioAvaliacaoNota->registrarNotificacao($con);
                    }
                    $nota = $itemCriterioAvaliacaoNota->getNota();
                }
            }
            $notasDaTurmaEmString .= sprintf("%s (%s)\n" .
                "= %s\n",
                Util::abreviaOuTruncaNome($inscricao->getNomeAluno(), 30),
                $inscricao->getMatriculaAluno(),
                ($nota == null ? "0F" : $nota) );
        }

        global $LIBERAR_NOTAS_TURMA;
        $strLog = sprintf("Foram lan�adas as notas de %s da turma da disciplina de %s, " .
                                "turno %s, grade %s, per�odo letivo %s\n<br/> " .
                                str_replace("\n", "<br/>", $notasDaTurmaEmString) ,
                                $itemCriterioAvaliacao->getRotulo() ,
                                $turma->getComponenteCurricular()->getSiglaDisciplina(),
                                $turma->getTurno(),
                                $turma->getGradeHorario(),
                                $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo() );
        $usuario->incluirLog($LIBERAR_NOTAS_TURMA,  $strLog, $con);
        
        mysql_query("COMMIT", $con);
        Header("Location: /coruja/espacoProfessor/pautaEletronica/pautaEletronicaAvaliacao_controle.php?idTurma=" . 
                $turma->getIdTurma() );
        exit;    
        
    } catch(Exception $ex) {
        mysql_query("ROLLBACK", $con);
        
        $msgsErro = array();
        $msgsErro[] = "Erro ao registrar lan�amento de notas: " . $ex->getMessage();
        include "$BASE_DIR/espacoProfessor/pautaEletronica/formPautaEletronicaAvaliacaoLancarNotas.php";
    }

} else if( $acao == "reabrirItemCriterioAvaliacaoNotas" ) {

    try {
        
        $con = BD::conectar();
        mysql_query("BEGIN", $con); // Inicia transa��o

        $itemCriterioAvaliacao = ItemCriterioAvaliacao::obterPorId($idItemCriterioAvaliacao);
        $turma->reabrirItemCriterioAvaliacao( $itemCriterioAvaliacao, $con );
        
        global $REABRIR_NOTAS_TURMA;
        $strLog = sprintf("Foram reabertas para apontamento as notas de %s da turma da disciplina de %s, " .
                                "turno %s, grade %s, per�odo letivo %s\n ",
                                $itemCriterioAvaliacao->getRotulo() ,
                                $turma->getComponenteCurricular()->getSiglaDisciplina(),
                                $turma->getTurno(),
                                $turma->getGradeHorario(),
                                $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo() );
        $usuario->incluirLog($REABRIR_NOTAS_TURMA,  $strLog, $con);
        
        mysql_query("COMMIT", $con);
        include "$BASE_DIR/espacoProfessor/pautaEletronica/formPautaEletronicaAvaliacaoLancarNotas.php";
        
    } catch(Exception $ex) {
        mysql_query("ROLLBACK", $con);
        
        $msgsErro = array();
        $msgsErro[] = "Erro ao registrar lan�amento de notas: " . $ex->getMessage();
        include "$BASE_DIR/espacoProfessor/pautaEletronica/formPautaEletronicaAvaliacao.php";
    }
    
} else {
    include "$BASE_DIR/espacoProfessor/pautaEletronica/formPautaEletronicaAvaliacao.php";
}
?>
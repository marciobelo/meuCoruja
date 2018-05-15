<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/Mensagem.php");
require_once("$BASE_DIR/classes/Log.php");

$idTurma = $_REQUEST["idTurma"];
$data = filter_input(INPUT_GET, "data", FILTER_SANITIZE_STRING);
$acao = $_REQUEST["acao"];

$login = $_SESSION["login"];

$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();

if( isset( $_SESSION["msgsErro"]) ) 
{
    $msgsErro = $_SESSION["msgsErro"];
    unset ($_SESSION["msgsErro"]);
}

if( !$turma->isPodeEditarPauta($login) ) 
{
    $msgsErro = array();
    $msgsErro[] = "Você não está autorizado a abrir a pauta eletrônica dessa turma";
    $professorLogado = Professor::getProfessorByIdPessoa( $login->getIdPessoa());
    $turmas = Turma::obterTurmasConfirmadasPorProfessor( $professorLogado );
    include "$BASE_DIR/espacoProfessor/index.php";
    exit;
}

if( $acao === "prepararLiberarPauta" ) 
{
    
    $qtdeTemposAulaApontados = $turma->obterQtdeTemposAulaApontados();
    $qtdeTotalTemposAulaEsperado = $turma->getComponenteCurricular()->getCargaHoraria();
    $percCumprimentoCarga = $qtdeTemposAulaApontados / $qtdeTotalTemposAulaEsperado * 100;
    $cumprimentoCargaOk = $percCumprimentoCarga >= 100;
    $estaoSituacoesDefinidas = $turma->isSituacoesAvaliacaoDefinidas();
    include "$BASE_DIR/espacoProfessor/pautaEletronica/formResumoFechamentoPauta.php";
    exit;

} 
else if( $acao === "liberarPauta" ) 
{
    $con = BD::conectar();
    try {
        mysql_query("BEGIN", $con); // Inicia transação
        
        $turma->liberarPautaParaSecretaria($con);
        
        // Mensagem para a secretaria que professor liberou as notas
        $corpo = sprintf("O professor(a) %s, da disciplina %s, turno %s, grade %s, período letivo %s, 
            liberou a pauta eletrônica para a secretaria acadêmica.",
                $professor->getNome(),
                $turma->getComponenteCurricular()->getSiglaDisciplina(),
                $turma->getTurno(),
                $turma->getGradeHorario(),
                $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo() );
        $arrIdPessoa = array();
        $arrIdPessoa[] = Config::SECRETARIA_ID_PESSOA;
        Mensagem::depositarMensagem("Pauta Liberada por Professor(a)", $corpo, $arrIdPessoa, $con);
        
        // Log de auditoria para o professor
        global $LIBERAR_PAUTA_TURMA;
        $strLog = sprintf("Liberada a pauta eletrônica da disciplina %s, turno %s, grade %s, período letivo %s",
                $turma->getComponenteCurricular()->getSiglaDisciplina(),
                $turma->getTurno(),
                $turma->getGradeHorario(),
                $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo() );
        $login->incluirLog($LIBERAR_PAUTA_TURMA,  $strLog, $con);
        if( $login->getIdPessoa() != $professor->getIdPessoa()) // se for a secretaria apontando
        {
            $descricao = "Sua pauta foi liberada por " . $login->getPessoa()->getNome() .
                    "\n<br/>" . $strLog;
            Log::incluirLog( $professor->getIdPessoa(), $REABRIR_DIA_LETIVO_TURMA, $descricao, $con);
        }
        
        mysql_query("COMMIT", $con);
        Header("Location: /coruja/espacoProfessor/index_controle.php?acao=exibirIndex");
        
    } catch(Exception $ex) {
        mysql_query("ROLLBACK", $con);
        $msgsErro = array();
        $msgsErro[] = "Erro ao liberar a turma para a secretaria acadêmica: " . $ex->getMessage();
        include "$BASE_DIR/espacoProfessor/pautaEletronica/formResumoFechamentoPauta.php";
    }
    exit;
    
} 
else if( !isset ($data) ) 
{
    $d = $turma->obterDataAulaMaisRecente();
    Header("Location: /coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=" .
            $d->getTurma()->getIdTurma() . "&data=" . $d->getData()->format("Y-m-d"));
} 
else 
{
    $dataDiaLetivoTurma = new DateTime($data);
    $listaDataDiaLetivo = $turma->obterDatasDiaLetivo();
    $d = $turma->obterDiaLetivoTurmaPorData($dataDiaLetivoTurma);
    $dmenos1 = $turma->obterDiaLetivoTurmaAnterior( $d->getData() );
    $dmais1 = $turma->obterDiaLetivoTurmaSeguinte( $d->getData() );
    $inscricoesDePauta = $turma->getInscricoesDePauta();
    
    $quadroNavegacao = $turma->gerarQuadroNavegacaoPautaTurma( $dataDiaLetivoTurma );
    $qtdeTotalDiasAnterior = $quadroNavegacao->getQtdeTotalDiasAnterior();
    $qtdeTotalDiasPosterior = $quadroNavegacao->getQtdeTotalDiasPosterior();
    $qtdeTotalDiasAnteriorEmAberto = $quadroNavegacao->getQtdeTotalDiasAnteriorEmAberto();
    $qtdeTotalDiasPosteriorEmAberto = $quadroNavegacao->getQtdeTotalDiasPosteriorEmAberto();

    include "$BASE_DIR/espacoProfessor/pautaEletronica/formPautaEletronica.php";
}
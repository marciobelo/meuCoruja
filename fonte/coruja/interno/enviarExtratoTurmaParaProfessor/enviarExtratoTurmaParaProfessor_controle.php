<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/Professor.php";

$acao = $_REQUEST["acao"];
$siglaCurso = $_REQUEST["siglaCurso"];
$idPeriodoLetivo = $_REQUEST["idPeriodoLetivo"];
$turno = $_REQUEST["turno"];

// Verifica Permiss�o
if(!$login->temPermissao($ENVIAR_EXTRATO_TURMA_PARA_PROFESSOR)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

if( !isset ($acao) ) { // a��o padr�o


    $idTurma = $_POST["idTurma"];
    $turma = Turma::getTurmaById($idTurma);
    $professor = $turma->getProfessor();

    if($professor==null) {
        $msg = "A turma n�o tem professor definido.";
    }
    if($turma->getTipoSituacaoTurma() != "FINALIZADA" ) {
        $msg = "A turma n�o est� finalizada.";
    }
    if( $professor != null && $professor->getEmail() == null ) {
        $msg = "O e-mail do professor n�o est� definido.";
    }

    require_once "$BASE_DIR/interno/enviarExtratoTurmaParaProfessor/telaConfirmaExtratoTurmaParaProfessor.php";
    
} else if($acao=="enviarExtratoTurmaParaProfessor") {
    $idTurma = $_POST["idTurma"];
    $turma = Turma::getTurmaById($idTurma);
    $professor = $turma->getProfessor();
    $texto = $turma->gerarExtratoTurmaParaProfessor();

    if($professor==null) {
        $msg = "A turma n�o tem professor definido.";
    }
    if($turma->getTipoSituacaoTurma() != "FINALIZADA" ) {
        $msg = "A turma n�o est� finalizada.";
    }
    if( $professor->getEmail() == null ) {
        $msg = "O e-mail do professor n�o est� definido.";
    }

    if(!isset($msg)) {
        try {
            Util::enviarEmail($professor->getEmail(), "Extrato de Turma", $texto);
            $msg = "E-mail enviado com sucesso.";
        } catch (Exception $ex) {
            $msg = "Erro no envio do e-mail. Tente mais tarde.";
        }
    }

    require_once "$BASE_DIR/interno/enviarExtratoTurmaParaProfessor/telaConfirmaExtratoTurmaParaProfessor.php";
} else if($acao=="voltar") {
    // volta para a tela de visualiza��o das turmas
    Header("location: /coruja/nort/controle/manterTurmas_controle.php?acao=exibirTurmas&siglaCurso=$siglaCurso&idPeriodoLetivo=$idPeriodoLetivo&turno=$turno");
}
?>
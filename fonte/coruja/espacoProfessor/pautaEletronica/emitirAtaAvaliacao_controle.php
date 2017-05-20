<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Usuario.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/Professor.php");

$idTurma = $_REQUEST["idTurma"];
$usuario = $_SESSION["usuario"];
$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();

// Verifica se o professor � o titular da turma informada
if( $professor->getIdPessoa() != $usuario->getIdPessoa() ) {
    $msgsErro = array();
    $msgsErro[] = "Voc� n�o est� autorizado a abrir a pauta eletr�nica dessa turma";
    $professorLogado = Professor::getProfessorByIdPessoa($usuario->getIdPessoa() );
    $turmas = Turma::obterTurmasConfirmadasPorProfessor( $professorLogado );
    include "$BASE_DIR/espacoProfessor/index.php";
}

include_once "$BASE_DIR/espacoProfessor/pautaEletronica/formAtaAvaliacao.php";
?>

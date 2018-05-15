<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/Professor.php");

$idTurma = $_REQUEST["idTurma"];
$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();

if( !$turma->isPodeEditarPauta($login) ) {
    $msgsErro = array();
    $msgsErro[] = "Você não está autorizado a abrir a pauta eletrônica dessa turma";
    $professorLogado = Professor::getProfessorByIdPessoa($login->getIdPessoa() );
    $turmas = Turma::obterTurmasConfirmadasPorProfessor( $professorLogado );
    include "$BASE_DIR/espacoProfessor/index.php";
}

include_once "$BASE_DIR/espacoProfessor/pautaEletronica/formAtaAvaliacao.php";
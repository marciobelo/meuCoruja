<?php
require_once "../includes/comum.php";
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/Turma.php");

$acao = filter_input(INPUT_GET, "acao", FILTER_SANITIZE_STRING);
$login = $_SESSION["login"];

switch( $acao ) {

    case "exibirIndex":

        $professor = Professor::getProfessorByIdPessoa( $login->getIdPessoa());
        $turmas = Turma::obterTurmasConfirmadasPorProfessor( $professor );

        include "$BASE_DIR/espacoProfessor/index.php";
        break;
    default:
        trigger_error("Ação não identificada.",E_USER_ERROR);
}
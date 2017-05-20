<?php
require_once "../includes/comum.php";
require_once("$BASE_DIR/classes/Usuario.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/Turma.php");

$acao = $_GET["acao"];
$usuario = $_SESSION["usuario"];

switch( $acao ) {

    case "exibirIndex":

        $professor = Professor::getProfessorByIdPessoa($usuario->getIdPessoa() );
        $turmas = Turma::obterTurmasConfirmadasPorProfessor( $professor );

        include "$BASE_DIR/espacoProfessor/index.php";
        break;
    default:
        trigger_error("Ação não identificada.",E_USER_ERROR);
}

?>

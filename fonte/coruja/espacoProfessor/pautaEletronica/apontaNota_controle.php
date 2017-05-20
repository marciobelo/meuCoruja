<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/Usuario.php");
require_once("$BASE_DIR/classes/Turma.php");
require_once("$BASE_DIR/classes/PeriodoLetivo.php");
require_once("$BASE_DIR/classes/Professor.php");
require_once("$BASE_DIR/classes/DiaLetivoTurma.php");
require_once("$BASE_DIR/classes/Mensagem.php");
require_once("$BASE_DIR/classes/Inscricao.php");

/*
 * Esse controlador responde com
 * Resposta � view formPaulaEletronicaAvaliacaoLancarNotas.php
 * ok sinaliza sucesso para a fun��o de callback do ajax
 * erro: blah blah sinaliza mensagem a ser exibida
 * qualquer coisa diferente: erro n�o trat�vel
 * ok (opera��o realizada com sucesso)
 * erro: <msg> (houve erro... msg detalha erro)
 */

$acao = $_POST["acao"];
$idTurma = $_POST["idTurma"];
$numMatriculaAluno = $_POST["numMatriculaAluno"];
$idItemCriterioAvaliacao = $_POST["idItemCriterioAvaliacao"];
$stringNota = $_POST["stringNota"];
$comentario = utf8_decode( $_POST["stringComentario"] );
$usuario = $_SESSION["usuario"];

$turma = Turma::getTurmaById($idTurma);
$professor = $turma->getProfessor();

// Verifica se o professor � o titular da turma informada
if( $professor->getIdPessoa() != $usuario->getIdPessoa() ) {
    echo "erro: Usu�rio n�o tem permiss�o para executar essa a��o!";
    exit;
}

switch( $acao ) {
    
    case "apontarNota":
        try {
            $itemCriterioAvaliacao = ItemCriterioAvaliacao::obterPorId($idItemCriterioAvaliacao);
            $inscricao = Inscricao::getInscricao($idTurma, $numMatriculaAluno);
            if( $turma->isAvaliacaoLiberada( $itemCriterioAvaliacao ) ) {
                echo utf8_encode( sprintf("erro: Notas de %s j� foram liberadas. Deve-se reabrir para ajustes.",
                        $itemCriterioAvaliacao->getRotulo() ) );
            } else {
                try {
                    if( $stringNota != "" ) {
                        $nota = Util::converteParaNota( $stringNota );
                    } else {
                        $nota = null;
                    }
                } catch (Exception $ex) {
                    echo utf8_encode( sprintf("erro: O valor %s n�o � v�lido.",
                        $stringNota ) );
                    exit;
                }
                $inscricao->lancarNota($itemCriterioAvaliacao, $nota, $comentario);
                echo "ok";
            }
            exit;
        } catch(Exception $ex) {
            echo utf8_encode( "erro: " . $ex->getMessage() );
            exit;
        }
        break;
}
?>

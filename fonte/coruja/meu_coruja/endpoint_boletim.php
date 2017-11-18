<?php

$BASE_DIR = __DIR__ . "/..";
require_once("$BASE_DIR/config.php");
require_once("$BASE_DIR/meu_coruja/valida_sessao.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");

function isNull($str){
    
    if($str == null){
        return "-";
    }else{
        return $str;
    }
}

$usuario = $_SESSION["usuario"];
$numMatriculaAluno = $usuario->getNomeAcesso();

$ma = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);

$inscricoes = $ma->obterInscricoesCursando();
$boletim = array();



foreach ($inscricoes as $inscricao) {
    $linha = array();
    $avaliacoes = array();
    $dadosUsuario = new stdClass();
    

    $dadosUsuario->siglaDisciplina = isNull($inscricao->getTurma()->getSiglaDisciplina());
    $dadosUsuario->nomeProfessor = isNull($inscricao->getTurma()->getProfessor()->getNome());
    $dadosUsuario->mediaFinal = isNull($inscricao->getMediaFinal());
    $dadosUsuario->faltas = isNull($inscricao->getTotalFaltas());
    $dadosUsuario->limiteFaltas = isNull($inscricao->getTurma()->getComponenteCurricular()->getLimiteFaltas());
    $itens = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();


    foreach ($itens as $item) {

        $avaliacao = new stdClass();
        
        $avaliacao->nota = isNull($item->getNota());
        $avaliacao->rotulo = isNull($item->getItemCriterioAvaliacao()->getRotulo());
        array_push($avaliacoes, $avaliacao);
    }

    array_push($linha, $dadosUsuario);
    array_push($linha, $avaliacoes);


    array_push($boletim, $linha);
}

//var_dump($boletim);


$jsonBoletim = json_encode($boletim, JSON_UNESCAPED_UNICODE);
echo($jsonBoletim);
?>
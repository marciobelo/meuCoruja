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

$inscricoes = $ma->obterInscricoesConcluidas();
$historico = array();

//var_dump($inscricoes);

foreach ($inscricoes as $inscricao) {
    $linha = array();
    $dadosUsuario = new stdClass();
    
    $dadosUsuario->siglaDisciplina = isNull($inscricao->getTurma()->getSiglaDisciplina());
    $dadosUsuario->situacao = isNull($inscricao->getSituacaoInscricao());
    $dadosUsuario->mediaFinal = isNull($inscricao->getMediaFinal());
    $dadosUsuario->faltas = isNull($inscricao->getTotalFaltas());
    
    
    array_push($historico, $dadosUsuario);
}

//var_dump($boletim);


$jsonHistorico = json_encode($historico, JSON_UNESCAPED_UNICODE);
echo($jsonHistorico);
?>

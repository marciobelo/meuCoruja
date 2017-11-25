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

function verificaPeriodo($dataInscricao){
    $array[0] = explode("-",$dataInscricao)[0];
    if(explode("-",$dataInscricao)[1]<6){
        $array[1]=1;
    }else{
        $array[1]=2;
    }
    return $periodoLetivo = implode(".",$array);
}

function verificaSituacao($siglaSituacao){
    if ($siglaSituacao == "RF"){
        return "Reprovado por falta";
    }elseif ($siglaSituacao == "RM"){
        return "Reprovado por média";
    }elseif($siglaSituacao == "APs"){
        return "Aprovado";
    }
}

$usuario = $_SESSION["usuario"];
$numMatriculaAluno = $usuario->getNomeAcesso();

$ma = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);

$inscricoes = $ma->obterInscricoesConcluidas();
$historico = array();

//var_dump($inscricoes);

foreach ($inscricoes as $inscricao) {
    $dadosUsuario = new stdClass();
    
    $dadosUsuario->siglaDisciplina = isNull($inscricao->getTurma()->getSiglaDisciplina());
    $dadosUsuario->situacao = verificaSituacao(isNull($inscricao->getSituacaoInscricao()));
    $dadosUsuario->mediaFinal = isNull($inscricao->getMediaFinal());
    $dadosUsuario->faltas = isNull($inscricao->getTotalFaltas());
    $dadosUsuario->periodoLetivo = verificaPeriodo(isNull($inscricao->getDataInscricao()));
    $dadosUsuario->cr = $ma->calcularCR();
    
    
    array_push($historico, $dadosUsuario);
}
    
    

//var_dump($historico);


$jsonHistorico = json_encode($historico, JSON_UNESCAPED_UNICODE);
echo($jsonHistorico);
?>

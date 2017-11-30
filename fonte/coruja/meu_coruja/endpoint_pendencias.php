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

$inscricoes = $ma->obterComponentesCurricularPendentes();

$pendencias = array();



foreach ($inscricoes as $inscricao) {
    $pendencia = new stdClass();
    $pendencia->siglaDisciplina = isNull($inscricao->getSiglaDisciplina());
    $pendencia->nomeDisciplina = isNull($inscricao->getNomeDisciplina());
    $pendencia->periodo = isNull($inscricao->getPeriodo());
    $pendencia->cargaHoraria = isNull($inscricao->getCargaHoraria());
    array_push($pendencias, $pendencia);
}

$jsonPendencias = json_encode($pendencias, JSON_UNESCAPED_UNICODE);
echo($jsonPendencias);
?>
